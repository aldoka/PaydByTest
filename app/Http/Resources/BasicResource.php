<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BasicResource extends JsonResource
{


    /** @var string JSON key for 'links' in REST responce */
    const RESPONCE_KEY_LINKS = 'links';
    /** @var string JSON key for 'self' in REST responce */
    const RESPONCE_KEY_SELF = 'self';
    /** @var string JSON key for 'data' in REST responce */
    const RESPONCE_KEY_DATA = 'data';
    /** @var string variable name for a model in a view */
    const VIEW_VARIABLE_ITEM = 'item';
    /** @var string a part of named router, which handles a 'GET one entity' request */
    const ROUTER_NAME_SHOW = 'show';

    /** @var string $routerName to GET a basic resource */
    protected $routerName;


    /**
     * BasicResource constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->setRouterName();
        return parent::__construct($resource);
    }


    /**
     * Sets GET router name, which will return an entity. Gets a resource name, adds 's' and '.show' to the end.
     * i.e. for 'Comment' resourse default router will be 'comments.show'.
     * Override this method if custom router needed.
     */
    protected function setRouterName()
    {
        $currentClassName = get_called_class();
        // 'Comment' -> 'comments.show'
        $this->routerName = lcfirst($currentClassName) . 's.' . static::ROUTER_NAME_SHOW;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            static::RESPONCE_KEY_LINKS => [
                static::RESPONCE_KEY_SELF => route($this->routerName, [static::VIEW_VARIABLE_ITEM => $this->id])
            ],
            static::RESPONCE_KEY_DATA => parent::toArray($request),
        ];
    }


}