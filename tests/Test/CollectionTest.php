<?php

namespace Test;
use Model\Entity\Collection;
use Provider\ContentEntity;
use Testes\Test\UnitAbstract;

/**
 * Tests the Set component.
 *
 * @category Sets
 * @package  Model
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class SetTest extends UnitAbstract
{
    private $set;

    public function setUp()
    {
        $this->set = new Collection('Provider\ContentEntity');

        for ($i = 1; $i <= 10; $i++) {
            $this->set[] = new ContentEntity([
                'id'   => $i,
                'name' => 'test ' . $i
            ]);
        }
    }

    public function aggregation()
    {
        $aggregated = $this->set->aggregate('id');

        // test aggregation count against the set count
        $this->assert(count($aggregated) === $this->set->count(), 'The aggregated items should match the length of the test set.');

        // test each aggregated item against each item in the set
        foreach ($this->set as $key => $item) {
            $this->assert(isset($aggregated[$key]) && $aggregated[$key] === $item->id, 'Item "' . $item->id . '" was not aggregated.');
        }
    }

    public function findingMany()
    {
        $query = function($item) {
            return preg_match('/^test [1-2]$/', $item->name);
        };

        $found = $this->set->find($query);

        $this->assert($found->count() === 2, 'Wrong number of items found.');
        $this->assert($found[0]->id === '1', 'The first item should have an id of 1.');
        $this->assert($found[1]->id === '2', 'The first item should have an id of 2.');

        $found = $this->set->find($query, 1);
        $this->assert($found->count() === 1, 'The query should have only found one item.');
        $this->assert($found[0]->id === '1', 'The item found should have had an id of 1.');

        $found = $this->set->find($query, 1, 1);
        $this->assert($found[0]->id === '2', 'The item found should have an id of 2.');
    }

    public function findingOne()
    {
        $found = $this->set->findOne(function($item) {
            return preg_match('/^test\s\d+$/', $item->name);
        });

        $this->assert($found instanceof ContentEntity, 'Item found should be an instance of an entity.');
        $this->assert($found->id === '1', 'The first item should have been returned.');
    }
}