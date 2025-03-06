<?php

namespace Local\Notifications;

interface IMessage
{
    public function getId();
    public function getUserList();
    public function getChannelList();
    public function getBody();
    public function getTitle();
    public function getUserParams();
    public function getDictionary();
    public function getDateInsert();
    public function getDateExec();
    public function getSuccessExec();

    public function setId($id);
    public function setUserList(array $array);
    public function setChannelList(array $array);
    public function setBody(string $body);
    public function setTitle(string $title);
    public function setUserParams(array $array);
    public function setDictionary(array $array);
    public function setDateInsert($date);
    public function setDateExec($date);
    public function setSuccessExec($status);

    public function toArray(): array;
}
