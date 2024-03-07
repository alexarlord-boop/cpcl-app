<?php

namespace App\Services;

class EntityDTO implements EntityInterface
{
    private string $section;
    private string $protocol;

    private string $type;
    private string $name;
    private string $description;
    private string $resource_location; // idp: metadata_url	op: discovery_url	rp: redirect_uri
    private ?string $entityid; // idp, sp: entityID or rp: client_id | nullable
    private ?string $dynamic_registration; // only in RP | nullable
    private ?string $client_secret; // only in RP | nullable

    public function __construct(
        string $section,
        string $protocol,
        string  $type,
        string  $name,
        string  $description,
        string  $resource_location,
        ?string $entityid = null,
        ?string $dynamic_registration = null,
        ?string $client_secret = null
    )
    {
        $this->section = $section;
        $this->protocol = $protocol;
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->resource_location = $resource_location;
        $this->entityid = $entityid;
        $this->dynamic_registration = $dynamic_registration;
        $this->client_secret = $client_secret;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEntityId(): ?string
    {
        return $this->entityid;
    }

    public function getResourceLocation(): string
    {
        return $this->resource_location;
    }

    public function getDynamicRegistration(): ?string
    {
        return $this->dynamic_registration;
    }

    public function getClientSecret(): ?string
    {
        return $this->client_secret;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProtocol(): string {
        return $this->protocol;
    }

    public function getSection(): string
    {
        return $this->section;
    }

    public function setSection(string $section): void
    {
        $this->$section = $section;
    }
}
