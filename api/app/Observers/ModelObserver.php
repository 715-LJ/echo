<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ModelObserver
{
    /**
     * 在提交所有事务后处理事件
     *
     * @var bool
     */
//    public bool $afterCommit = true;

    /**
     * Handle the BaseModel "created" event.
     */
    public function created(Model $model): void
    {
    }

    /**
     * Handle the BaseModel "updated" event.
     */
    public function updated(Model $model): void
    {
    }

    /**
     * Handle the BaseModel "updating" event.
     */
    public function saving(Model $model): void
    {
        if ($model->isOperationLog && $model->getDirty()) {
            $objectId = (int)$model->id;
            $data     = [
                'request_id' => REQUEST_ID,
                'uid'        => Auth::id() ?? 0,
                'table'      => $model->getTable(),
                'event'      => $objectId ? 'update' : 'create',
                'object_id'  => $objectId,
                'origin'     => json_encode($model->getOriginal()),
                'change'     => json_encode($model->getDirty())
            ];
//            OperationLogJob::dispatch($data);
        }
    }

    /**
     * Handle the BaseModel "deleted" event.
     */
    public function deleting(Model $model): void
    {
        if ($model->isOperationLog) {
            $data = [
                'request_id' => REQUEST_ID,
                'uid'        => Auth::id(),
                'table'      => $model->getTable(),
                'event'      => 'delete',
                'object_id'  => $model->id,
            ];
//            OperationLogJob::dispatch($data);
        }
    }

    /**
     * Handle the BaseModel "restored" event.
     */
    public function restored(Model $model): void
    {
        //
    }

    /**
     * Handle the BaseModel "force deleted" event.
     */
    public function forceDeleted(Model $model): void
    {
    }
}
