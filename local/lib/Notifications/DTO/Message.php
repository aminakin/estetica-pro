<?php

namespace Local\Notifications\DTO;

use Local\Notifications\IMessage;

class Message implements IMessage
{
    private $id;
    private array $channelList = [];
    private string $title = '';
    private string $body = '';
    private array $userList = [];
    private array $userParams = []; // map: userId => {user specific params}
    private array $dictionary = []; // map: key => value
    private int $expiry = 0;
    private $dateInsert;
    private $dateExec;
    private $successExec;

    /** @params array|null $data */
    public function __construct($data = null)
    {
        if (!empty($data['userList']))
            $this->setUserList($data['userList']);
        if (!empty($data['title']))
            $this->setTitle($data['title']);
        if (!empty($data['body']))
            $this->setBody($data['body']);
        if (!empty($data['channelList']))
            $this->setChannelList($data['channelList']);
        if (!empty($data['dictionary']))
            $this->setDictionary($data['dictionary']);
        if (!empty($data['userParams']))
            $this->setUserParams($data['userParams']);
        if (!empty($data['id']))
            $this->setId($data['id']);
        if (!empty($data['dateInsert']))
            $this->setDateInsert($data['dateInsert']);
        if (!empty($data['dateExec']))
            $this->setDateExec($data['dateExec']);
        if (!empty($data['successExec']))
            $this->setSuccessExec($data['successExec']);
    }

    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    public function getDateExec()
    {
        return $this->dateExec;
    }

    public function getSuccessExec()
    {
        return $this->successExec;
    }

    public function setDateInsert($date)
    {
        $this->dateInsert = $date;
    }

    public function setDateExec($date)
    {
        $this->dateExec = $date;
    }

    public function setSuccessExec($status)
    {
        $this->successExec = $status;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserList()
    {
        return $this->userList;
    }

    public function getChannelList()
    {
        return $this->channelList;
    }

    public function getBody()
    {
        return $this->body;

    }

    public function getUserParams()
    {
        return $this->userParams;
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

    public function setUserList(array $array)
    {
        $this->userList = $array;
    }

    public function setChannelList(array $array)
    {
        $this->channelList = $array;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function setUserParams(array $array)
    {
        $this->userParams = $array;
    }

    public function setDictionary(array $array)
    {
        $this->dictionary = $array;
    }

    public function getId()
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'userList' => $this->userList,
            'channelList' => $this->channelList,
            'body' => $this->body,
            'title' => $this->title,
            'userParams' => $this->userParams,
            'dictionary' => $this->dictionary ?? [],
            'dateInsert' => $this->dateInsert,
            'dateExec' => $this->dateExec,
            'successExec' => $this->successExec,
        ];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}
