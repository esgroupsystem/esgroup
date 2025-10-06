<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Record;

class RecordController extends Controller
{
    public function index() {
        return response()->json(Record::all());
    }

    public function store(Request $request) {
        $record = Record::create($request->all());
        return response()->json($record, 201);
    }

    public function show($id) {
        return response()->json(Record::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $record = Record::findOrFail($id);
        $record->update($request->all());
        return response()->json($record, 200);
    }

    public function destroy($id) {
        Record::destroy($id);
        return response()->json(null, 204);
    }
}
