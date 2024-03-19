<?php

namespace BinaryCats\Rbac\Actions;

use Lorisleiva\Actions\Action;
use Spatie\Permission\Contracts\Permission;

class StorePermission extends Action
{
    /**
     * @param \Spatie\Permission\Contracts\Permission $permission
     */
    public function __construct(
        protected Permission $permission
    ) {
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:125'],
            'guard_name' => ['required', 'string', 'min:2', 'max:125'],
        ];
    }

    /**
     * @param array $attributes
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function handle(array $attributes): Permission
    {
        $this->fill($attributes);

        return $this->permission->firstOrCreate($this->validateAttributes());
    }
}
