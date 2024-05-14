<?php

namespace App\Enums;

enum RoomType: string
{
   case SMALL = 'sm';
   case MEDIUM = 'md';
   case LARGE = 'lg';
   case EXTRA_LARGE = 'xl';
}