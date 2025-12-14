<?php

namespace App\Enums;

enum Action: string
{
    case SEND_MESSAGE = 'send_message';
    case ADD_TO_GROUP = 'add_to_group';
    case ADD_TO_COMMUNITY = 'add_to_community';

}
