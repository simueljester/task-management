<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;

class BaseRepository
{
    public $model;

    /**
     * Query model
     *
     * @return void
     */
    public function query()
    {
        $model = $this->model->query();

        return $model;
    }

    /**
     * Save model
     *
     * @return void
     */
    public function save(array $record)
    {
        $this->model->fill($record);

        DB::beginTransaction();

        try {
            $this->model->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return $this->model;
    }

    /**
     * Update model
     *
     * @return void
     */
    public function update(int $id, array $record)
    {

        DB::beginTransaction();
        try {
            $model = $this->model->find($id);
            $model->fill($record);
            $model->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return $model;
    }

    /**
     * Delete model
     *
     * @return bool
     */
    public function delete(int $id)
    {

        DB::beginTransaction();

        try {
            $model = $this->model->find($id);
            $isDeleted = $model->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return $isDeleted;
    }

    /**
     * Find model
     *
     * @return void
     */
    public function find(int $id)
    {
        $model = $this->model->find($id);

        return $model;
    }

    /**
     * Find model and toggle the is_active field
     *
     * @return void
     */
    public function toggleIsActive(int $id)
    {
        $model = $this->model->find($id);

        if (! $model) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $model->is_active = ! $model->is_active;
        $model->save();

        return $model;
    }
}
