<?php

namespace App\Services;

use App\Enums\EntityProtocol;
use App\Enums\EntityType;
use App\Enums\Section;

class EntityFactory
{

    public static function createEntity(string $type, array $data): ?EntityDTO
    {
        return match ($type) {
            EntityType::IDP => self::createIdp($data),
            EntityType::IDPS => self::createIdps($data),
            EntityType::SP => self::createSp($data),
            EntityType::SPS => self::createSps($data),
            EntityType::OP => self::createOp($data),
            EntityType::RP => self::createRp($data),
            default => null,
        };
    }

    public static function createIdp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::IN,
            EntityProtocol::SAML,
            EntityType::IDP,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            $data['entityid'],
            null,
            null
        );
    }

    public static function createIdps(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::IN,
            EntityProtocol::SAML,
            EntityType::IDPS,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            null,
            null,
            null
        );
    }

    public static function createOp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::IN,
            EntityProtocol::OIDC,
            EntityType::OP,
            $data['name'],
            $data['description'],
            $data['discovery_url'],
            null,
            null,
            null
        );
    }

    public static function createSp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::OUT,
            EntityProtocol::SAML,
            EntityType::SP,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            $data["entityid"],
            null,
            null
        );
    }

    public static function createSps(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::OUT,
            EntityProtocol::SAML,
            EntityType::SPS,
            $data['name'],
            $data['description'],
            $data['metadata_url'],
            null,
            null,
            null
        );
    }

    public static function createRp(array $data): ?EntityDTO
    {
        return new EntityDTO(
            Section::OUT,
            EntityProtocol::OIDC,
            EntityType::RP,
            $data['name'],
            $data['description'],
            $data['redirect_uri'],
            $data["client_id"],
            $data["dynamic_registration"],
            $data["client_secret"]
        );
    }
}
