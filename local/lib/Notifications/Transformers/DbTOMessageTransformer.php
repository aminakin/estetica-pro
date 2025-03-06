<?php

namespace Local\Notifications\Transformers;

class DbTOMessageTransformer
{
    public static function transform(array $data): array
    {
        return [
            'id' => $data['ID'],
            'channelList' => json_decode($data['CHANNEL_LIST']),
            'body' => $data['BODY'],
            'title' => $data['TITLE'],
            'userList' => json_decode($data['USER_LIST']),
            'dictionary' => (array)json_decode($data['DICTIONARY']),
            'userParams' => (array)json_decode($data['USER_PARAMS']),
            'dateInsert' => $data['DATE_INSERT'],
            'dateExec' => $data['DATE_EXEC'],
            'successExec' => $data['SUCCESS_EXEC'],
        ];
    }
}
