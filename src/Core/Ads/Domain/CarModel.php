<?php

namespace App\Core\Ads\Domain;

class CarModel
{
    const MAX_POSITION_RESEARCH = 99;

    private CarModelId $id;
    private string $name;
    private string $manufacturer;

    public function __construct(string $name, string $manufacturer)
    {
        $this->id = new CarModelId();
        $this->name = $name;
        $this->manufacturer = $manufacturer;
    }

    public function getId(): CarModelId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    /**
     * Return strpos of the research, the length is not the only good Heuristic...
     * @return array  [ position in the name, length of the model]
     */
    public function matchingTheSearchString(string $search): array
    {
        if (str_contains($s = $this->sanitize($search), $n = $this->sanitize($this->name))) {
            return [strpos($s, $n), strlen($n)];
        }

        return [self::MAX_POSITION_RESEARCH, 0];
    }

    private function sanitize(string $subject): string
    {
        return strtolower(preg_replace('/\s+/', '', $subject));
    }
}