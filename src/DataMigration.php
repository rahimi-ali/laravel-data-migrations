<?php

namespace AliRahimiCoder\LaravelDataMigrations;

use Illuminate\Support\Facades\DB;

abstract class DataMigration
{
    protected string $table = '';

    protected bool $exclusive = false;

    /**
     * @return array<int, string>
     */
    abstract public function columns(): array;

    /**
     * @return array<int, string>
     */
    abstract public function uniqueColumns(): array;

    /**
     * @return array<int, array<int, mixed>>
     */
    abstract public function data(): array;

    public function run(): void
    {
        $existingIndices = $this->findExistingIndices();

        $newData = $this->getNewData($existingIndices);

        $this->insert($newData);

        if ($this->exclusive) {
            $this->deleteOtherRows();
        }
    }

    public function findExistingIndices(): array
    {
        $keyedData = $this->getKeyedUniqueData();

        $existingIndexes = [];
        foreach ($keyedData as $index => $keyedDatum) {
            $exists = DB::table($this->table)->where($keyedDatum)->first() !== null;
            if ($exists) {
                $existingIndexes[] = $index;
            }
        }

        return $existingIndexes;
    }

    protected function getNewData(array $existingIndices): array
    {
        $data = $this->getKeyedData();

        $newData = [];
        foreach ($data as $index => $datum) {
            if (!in_array($index, $existingIndices)) {
                $newData[] = $datum;
            }
        }

        return $newData;
    }

    protected function insert(array $data): bool
    {
        return DB::table($this->table)->insert($data);
    }

    protected function deleteOtherRows(): int
    {
        $keyedUniqueData = $this->getKeyedUniqueData();

        $query = DB::table($this->table);

        foreach ($keyedUniqueData as $keyedDatum) {
            $query->whereNot($keyedDatum);
        }

        return $query->delete();
    }

    protected function getKeyedData(): array
    {
        $columns = $this->columns();
        $data = $this->data();

        $keyedData = [];
        foreach ($data as $datum) {
            $keyedData[] = array_combine($columns, $datum);
        }

        return $keyedData;
    }

    protected function getKeyedUniqueData(): array
    {
        $uniqueColumns = $this->uniqueColumns();
        $keyedData = $this->getKeyedData();

        $keyedUniqueData = [];
        foreach ($keyedData as $datum) {
            foreach ($datum as $key => $v) {
                if (!in_array($key, $uniqueColumns)) {
                    unset($datum[$key]);
                }
            }
            $keyedUniqueData[] = $datum;
        }

        return $keyedUniqueData;
    }

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }
}