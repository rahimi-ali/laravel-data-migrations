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

        $newData =  $this->getNewData($existingIndices);

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
        $data = $this->data();

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

    protected function getKeyedUniqueData(): array
    {
        $columns = $this->columns();
        $uniqueColumns = $this->uniqueColumns();
        $data = $this->data();

        $keyedUniqueData = [];
        foreach ($data as $datum) {
            $keyedDatum = array_combine($columns, $datum);
            foreach ($keyedDatum as $key => $v) {
                if (!in_array($key, $uniqueColumns)) {
                    unset($keyedDatum[$key]);
                }
            }
            $keyedUniqueData[] = $keyedDatum;
        }

        return $keyedUniqueData;
    }

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }
}