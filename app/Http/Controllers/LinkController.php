<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Validator;

class LinkController extends Controller
{
    public function createLink (Request $request) {
        $validator = Validator::make($request->all(), [
            'link_name' => 'required|unique:link,link_name',
            'link_url' => 'required|url|unique:link,link_url',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $link = new Link();
        $link->link_name = $request->input('link_name');
        $link->link_url = $request->input('link_url');
        $link->save();
        return response([
            'status' => 1,
        ]);
    }
    public function delete (Request $request) {
        $validator = Validator::make($request->all(), [
            'link_id' => 'required',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $link = Link::find($request->input('link_id'));
        if ($link->id === 1 || $link->id === 2) return response("Can not delete portal's link", 403);
        $link->delete();
        return response([
            'status' => 1,
        ]);
    }
}
