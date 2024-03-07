<?php

namespace App\Services;
use Symfony\Component\Yaml\Yaml;
use App\Models\Entity;


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

    public function extractEntities($yamlData): void
    {
        // Your logic to extract entities from parsed data
        $entitiesTmp = array();
        foreach ($this->entityTypes as $entityType => [$direction, $entityProtocol]) {
            $entityData = $yamlData[$direction][$entityType] ?? null;
            if ($entityData) {
                $parsedDTO = EntityFactory::createEntity($entityType, $entityData);
                $entitiesTmp[] = $parsedDTO;
//                Entity::create([
//                    'section' => $parsedDTO->getSection(),
//                    'protocol' => $parsedDTO->getProtocol(),
//                    'type' => $parsedDTO->getType(),
//                    'name' => $parsedDTO->getName(),
//                    'description' => $parsedDTO->getDescription(),
//                    'resource_location' => $parsedDTO->getResourceLocation(),
//                    'entityid' => $parsedDTO->getEntityId(),
//                    'dynamic_registration' => $parsedDTO->getDynamicRegistration(),
//                    'client_secret' => $parsedDTO->getClientSecret(),
//                ]);
            }
        }

        $this->setEntities($entitiesTmp);


    }

}
