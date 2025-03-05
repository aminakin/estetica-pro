<?php

namespace ApiLib\Response;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class JsonResponseAbstract
{
    /**
     * Трансформер данных
     * @param $data
     * @return array
     */
    abstract public static function data($data): array;

    /*
     * Общий способ отправки данных - коллекция
     * @param $data
     * @return array
     */
    public static function collection($data):array
    {
        $fractal = new Manager();

        $resource = new Collection($data, function ($data){
            return static::data($data);
        });

        return $fractal->createData($resource)->toArray()['data'];
    }

    /**
     * Общий способ отправки данных - один массив
     * @param $data
     * @return array
     */
    public static function item($data)
    {
        $fractal = new Manager();

        $resource = new Item($data, function ($data){
            return static::data($data);
        });

        return $fractal->createData($resource)->toArray()['data'];
    }
}