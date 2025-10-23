<?php

namespace SunnyFlail\DoctrineMigrationSchemaUpdater\SchemaUpdater\ValueObject;

enum MatchOptionEnum: string
{
    case FULL = 'FULL';
    case SIMPLE = 'SIMPLE';
    case PARTIAL = 'PARTIAL';
}
