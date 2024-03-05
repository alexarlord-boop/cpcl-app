<?php

namespace App\Services;
use Symfony\Component\Yaml\Yaml;

class Parser
{

    private array $entityTypes = [
        'saml_idp' => ['in', 'saml'],
        'saml_idps' => ['in', 'saml'],
        'saml_sp' => ['out', 'saml'],
        'saml_sps' => ['out', 'saml'],
        'oidc_op' => ['in', 'oidc'],
        'oidc_rp' => ['out', 'oidc'],
    ];

    private array $directionFilepath = [
        'in' => 'saml2O-idp-remote.php',
        'out' => 'saml2O-sp-remote.php',
    ];

    private array $entities;

    private function setEntities($entities)
    {
        $this->entities = $entities;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }
    public function parseYamlFile($fileContent)
    {
        // Your YAML parsing logic here
        return Yaml::parse($fileContent);
    }

    public function extractEntities($data)
    {
        // Your logic to extract entities from parsed data
        $entitiesTmp = array();
        foreach ($this->entityTypes as $entityType => [$direction, $entityProtocol]) {
            $entityData = $yamlData[$direction][$entityType] ?? null;
            if ($entityData) {
                $entity = EntityFactory::createEntity($entityType, $entityData);
                $entitiesTmp[] = $entity;
            }
        }

        $this->setEntities($entitiesTmp);
    }

}
