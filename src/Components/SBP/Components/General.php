<?php declare(strict_types=1);

namespace FroshPluginUploader\Components\SBP\Components;

class General extends AbstractComponent
{
    /**
     * @var array
     */
    private $allData;

    public function getCompatibleShopwareVersions(string $minVersion, ?string $maxVersion): \Generator
    {
        $versions = $this->getData()['softwareVersions'];

        foreach ($versions as $version) {
            if (!$version['selectable']) {
                continue;
            }
            
            $versionName = $version['name'];
            $versionSplit = explode('-', $versionName);
            $versionName = $versionSplit[0];

            if (version_compare($versionName, $minVersion, '>=') && ($maxVersion === null || version_compare($versionName, $maxVersion, '<='))) {
                $version['children'] = [];
                yield $version;
            }
        }
    }

    public function getLocalizations(): array
    {
        return $this->getData()['localizations'];
    }

    public function all(): array
    {
        return $this->getData();
    }

    private function getData(): array
    {
        if ($this->allData === null) {
            $this->allData = json_decode((string) $this->client->get('/pluginstatics/all')->getBody(), true);
        }

        return $this->allData;
    }
}
