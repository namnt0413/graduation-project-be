<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ItemRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function create(ItemRequest $request) {
        $newItem = Item::create($request->validated());
        return response([
            'item' => $newItem,
            'message' => 'create new item success'
        ], 200);
    }

    public function detail($id)
    {
        $item = Item::where(["id" => $id])->first();
        return response([
            'data' => $item,
            'message' => 'OK'
        ], 200);
    }

    public function updateItemTitle(Request $request, $id) {
        $item = Item::findOrFail($id);
        $item->update([
            'title' => $request->title,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateItemContent(Request $request, $id) {
        $item = Item::findOrFail($id);
        $item->update([
            'content' => $request->content,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function delete($id)
    {
        try {
            Item::find($id)->delete();
            return response([
                'message' => 'OK'
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Message: ' . $exception->getMessage() . ' --- Line : ' . $exception->getLine());
            return response()->json([
                'code' => 500,
                'message' => 'delete failed'
            ], 400);
        }

    }

}
