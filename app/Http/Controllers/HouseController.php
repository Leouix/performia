<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    public function getData(Request $request) {

        $name = $request->input('name');
        $priceMin = $request->input('priceMin');
        $priceMax = $request->input('priceMax');
        $bedrooms = $request->input('bedrooms');
        $bathrooms = $request->input('bathrooms');
        $storeys = $request->input('storeys');
        $garages = $request->input('garages');

        $query = House::query();

        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if(!empty($priceMin) || !empty($priceMax)) {
            if(empty($priceMin)) $priceMin = 0;
            if(empty($priceMax)) $priceMax = 9999999999999;
            $query->whereBetween('price', [$priceMin, $priceMax]);
        }

        if (!empty($bedrooms)) {
            $query->where('bedrooms', $bedrooms);
        }

        if (!empty($bathrooms)) {
            $query->where('bathrooms', $bathrooms);
        }

        if (!empty($storeys)) {
            $query->where('storeys', $storeys);
        }

        if (!empty($garages)) {
            $query->where('garages', $garages);
        }

        $data = $query->get();

        return json_encode($data);

    }
}
