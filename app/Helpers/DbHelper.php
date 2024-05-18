<?php

namespace App\Helpers;

class DbHelper
{
    public static function store(Request $request, string $modelType, array $requestedObjectFields)
    {
        $data = $request->only($requestedObjectFields);

        try {
            DB::beginTransaction();
            $user = DbHelper::createModelByItsType($modelType, $data);
            DB::commit();
        } catch (QueryException $dbError) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'message' => $dbError->getMessage(),
                'code'    => 'db/error',
            ]);
        } catch (\Exception $error) {
            DB::rollBack();

            throw $error;
       }
    }

    public static function findOrFail($id, string $modelType)
    {
        $model = $this->findModelByItsType($id);

        if (!$model) {
            throw new ModelNotFoundException("The model with given ID is does not exist: $id");
        }

        return $model;
    }

    private static function createModelByItsType(string $ype, $data)
    {
        switch (strtolower($type)) {
            case 'skill':
                return Skills::create($data);
        }
    }


    private static function findModelByItsType(string $type, $id)
    {
        switch (strtolower($modelType)) {
            case 'skill':
                return Skill::find($id);
            case 'user':
                return User::find($id);
        }
    }
}
