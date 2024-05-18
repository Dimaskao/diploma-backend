<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Database\QueryException;
use DB;

class DbHelper
{
    public static function store($request, $modelType, array $requestedObjectFields)
    {
        $data = $request->only($requestedObjectFields);

        try {
            DB::beginTransaction();
            $model = DbHelper::createModelByItsType($modelType, $data);
            DB::commit();
            return $model;
        } catch (QueryException $dbError) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $dbError->getMessage(),
                'code' => 'db/error',
            ]);
        } catch (\Exception $error) {
            DB::rollBack();
            throw $error;
        }
    }

    public static function update($request, array $validator, $modelType, $id)
    {
        try {
            $model = DbHelper::findOrFail($id . $modelType);
            $validatedData = $request->validate($validator);
            $model->update($validatedData);
            return response()->json([$modelType => $model], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Unexpected error occurred during updating the model: $modelType, id: $id"], 500);
        }
    }

    public static function destroy($id, $modelType)
    {
        try {
            $model = DbHelper::findOrFail($id, $modelType);
            $model->delete();
            return response()->json([
                'message' => "Model $modelType with id $id was removed."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "A problem occurred while removing the model $modelType with id $id"
            ], 500);
        }
    }

    public static function findOrFail($id, $modelType)
    {
        $model = DbHelper::findModelByItsType($modelType, $id);

        if (!$model) {
            throw new \Exception("The model with given ID is does not exist: $id");
        }

        return $model;
    }

    private static function createModelByItsType($type, $data)
    {
        switch (strtolower($type)) {
            case 'skill':
                return Skill::create($data);
        }
    }


    private static function findModelByItsType(string $type, $id)
    {
        switch (strtolower($type)) {
            case 'skill':
                return Skill::find($id);
            case 'user':
                return User::find($id);
        }
    }
}
