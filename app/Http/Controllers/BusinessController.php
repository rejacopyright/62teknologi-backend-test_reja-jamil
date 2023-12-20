<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as req;
use Illuminate\Support\Facades\Validator;
use App\Models\business;
use App\Models\attributes;
use App\Models\location;
use App\Models\categories;
use App\Models\term;

class BusinessController extends Controller
{
    public function __construct()
    {
        Validator::extendImplicit(
            'allowed_fields',
            function ($attribute, $value, $parameters, $validator) {
                if (strpos($attribute, '.') === false) {
                    return in_array($attribute, $parameters);
                }
                if (is_array($value)) {
                    return empty(array_diff_key($value, array_flip($parameters)));
                }
                foreach ($parameters as $parameter) {
                    if (substr_compare($attribute, $parameter, -strlen($parameter)) === 0) {
                        return true;
                    }
                }
                return false;
            }
        );
    }

    function getBusiness(req $r)
    {
        // VALIDATOR
        $allowed_fields = ['location', 'sort_by', 'categories', 'term', 'attributes'];
        $v_rules = [
            '*' => 'allowed_fields:' . join(',', $allowed_fields) . '',
            'location' => 'required',
            'sort_by' => 'in:best_match,rating,review,distance',
            // 'categories' => 'not_in:null',
        ];
        $v_attr = [
            'location' => 'location',
            'sort_by' => 'sort_by',
            // 'category_id' => 'Kategori',
        ];
        $v_locale = [
            'required' => 'Parameter :attribute harus di isi',
            'in' => 'Parameter :attribute harus di isi antara (best_match, rating, review, atau distance)',
            'allowed_fields' => 'Parameter :attribute tidak di izinkan hanya (' . join(', ', $allowed_fields) . ') yang di izinkan',
        ];

        $v = Validator::make($r->all(), $v_rules, $v_locale, $v_attr);
        if ($v->fails()) {
            return response()->json(['status' => 422, 'data' => $v->errors(), 'req' => $r->all()]);
        }

        $business = new business;
        if ($r->has('location')) {
            $business = $business->whereHas('location', function ($q) use ($r) {
                $q->where('name', 'like', '%' . $r->location . '%');
            });
        }
        if ($r->has('categories')) {
            $business = $business->whereHas('category', function ($q) use ($r) {
                $q->where('name', 'LIKE', '%' . explode(',', $r->categories)[0] . '%');
                foreach (explode(',', $r->categories) as $cat) {
                    $q->orWhere('name', 'LIKE', '%' . $cat . '%');
                }
            });
        }
        if ($r->has('term')) {
            $business = $business->whereHas('term', function ($q) use ($r) {
                $q->where('name', 'LIKE', '%' . explode(',', $r->term)[0] . '%');
                foreach (explode(',', $r->term) as $trm) {
                    $q->orWhere('name', 'LIKE', '%' . $trm . '%');
                }
            });
        }
        if ($r->has('sort_by')) {
            if ($r->sort_by === 'rating') {
                $business = $business->orderBy('rating', 'desc');
            } else if ($r->sort_by === 'review') {
                $business = $business->orderBy('review_count', 'desc');
            } else if ($r->sort_by === 'distance') {
                $business = $business->orderBy('latitude', 'asc');
            } else if ($r->sort_by === 'best_match') {
                $business = $business->orderBy('name', 'asc');
            }
        }
        if ($r->has('attributes')) {
            $attribute_ids = attributes::where(function ($q) use ($r) {
                $attr_arr = explode(',', $r->input('attributes'));
                $q->where('name', 'LIKE', '%' . $attr_arr[0] . '%');
                foreach ($attr_arr as $attr) {
                    $q->orWhere('name', 'LIKE', '%' . $attr . '%');
                }
            });

            $business = $business
                ->where(function ($q) use ($attribute_ids) {
                    $ids = $attribute_ids->distinct('id')->pluck('id')->all();
                    $q->where('attribute_ids', 'LIKE', '%' . $ids[0] . '%');
                    foreach ($ids as $attr_id) {
                        $q->orWhere('attribute_ids', 'LIKE', '%' . $attr_id . '%');
                    }
                });
        }
        return $business->with(['location', 'category', 'term'])->paginate(10)->through(function ($m) {
            $m->attributes = attributes::whereIn('id', $m->attribute_ids)->distinct('name')->pluck('name')->all();
            return collect($m)->except(['attribute_ids']);
        });
    }

    function createBusiness(req $r)
    {
        // VALIDATOR
        $allowed_fields = [
            'name',
            'location_id',
            'category_id',
            'term_id',
            'attribute_ids',
            'address',
            'latitude',
            'longitude',
        ];
        $v_rules = [
            '*' => 'allowed_fields:' . join(',', $allowed_fields) . '',
            'name' => 'required',
            'attribute_ids' => 'array',
        ];
        $v_locale = [
            'required' => 'Parameter :attribute harus di isi',
            'array' => 'Parameter :attribute harus array string',
            'allowed_fields' => 'Parameter :attribute tidak di izinkan hanya (' . join(', ', $allowed_fields) . ') yang di izinkan',
        ];

        $v = Validator::make($r->all(), $v_rules, $v_locale, []);
        if ($v->fails()) {
            return response()->json(['status' => 422, 'data' => $v->errors(), 'req' => $r->all()]);
        }

        // Store
        $business = new business;
        $business->name = $r->name;
        $business->location_id = $r->location_id ?? location::first()->id;
        $business->category_id = $r->category_id ?? categories::first()->id;
        $business->term_id = $r->term_id ?? term::first()->id;
        $business->attribute_ids = $r->attribute_ids ?? attributes::limit(10)->distinct('id')->pluck('id')->all();
        $business->address = $r->address;
        $business->latitude = $r->latitude;
        $business->longitude = $r->longitude;
        $business->save();
        return response()->json(['status' => 200, 'success' => true, 'message' => "Berhasil menambahkan $business->name", 'data' => $business]);
    }

    function updateBusiness(req $r, $id)
    {
        // VALIDATOR
        $allowed_fields = [
            'name',
            'location_id',
            'category_id',
            'term_id',
            'attribute_ids',
            'address',
            'latitude',
            'longitude',
        ];
        $v_rules = [
            '*' => 'allowed_fields:' . join(',', $allowed_fields) . '',
            'name' => 'required',
            'attribute_ids' => 'array',
        ];
        $v_locale = [
            'required' => 'Parameter :attribute harus di isi',
            'array' => 'Parameter :attribute harus array string',
            'allowed_fields' => 'Parameter :attribute tidak di izinkan hanya (' . join(', ', $allowed_fields) . ') yang di izinkan',
        ];

        $v = Validator::make($r->all(), $v_rules, $v_locale, []);
        if ($v->fails()) {
            return response()->json(['status' => 422, 'data' => $v->errors(), 'req' => $r->all()]);
        }

        // Store
        $business = business::findOrFail($id);
        $business_name = $business->name;
        $business->name = $r->name;
        $business->location_id = $r->location_id ?? location::first()->id;
        $business->category_id = $r->category_id ?? categories::first()->id;
        $business->term_id = $r->term_id ?? term::first()->id;
        $business->attribute_ids = $r->attribute_ids ?? attributes::limit(10)->distinct('id')->pluck('id')->all();
        $business->address = $r->address;
        $business->latitude = $r->latitude;
        $business->longitude = $r->longitude;
        $business->save();
        return response()->json(['status' => 200, 'success' => true, 'message' => "Berhasil mengubah $business_name", 'data' => $business]);
    }

    function deleteBusiness($id)
    {
        $business = business::findOrFail($id);
        $business_name = $business->name;
        $business->delete();
        return response()->json(['success' => true, 'message' => "Berhasil menghapus $business_name"]);
    }
}
