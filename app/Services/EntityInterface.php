<?php

namespace App\Services;

interface EntityInterface
{
    public function getType(): string;

    public function getProtocol(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getResourceLocation(): string;

    public function getId(): ?string;

    public function getDynamicRegistration(): ?string;

    public function getClientSecret(): ?string;
}
