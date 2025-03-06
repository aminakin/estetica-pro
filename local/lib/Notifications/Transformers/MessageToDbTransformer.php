<?php

namespace Local\Notifications\Transformers;

class MessageToDbTransformer
{
    public static function transform(array $data): array
    {
        return [
            'USER_LIST' => json_encode($data['userList']),
            'CHANNEL_LIST' => json_encode($data['channelList']),
            'BODY' => $data['body'],
            'TITLE' => $data['title'],
            'USER_PARAMS' => json_encode($data['userParams']),
            'DICTIONARY' => json_encode($data['dictionary'] ?? []),
            'DATE_INSERT' => $data['dateInsert'],
            'DATE_EXEC' => $data['dateExec'],
            'SUCCESS_EXEC' => $data['successExec'],
        ];
    }
}
