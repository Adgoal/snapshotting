<?php
/**
 * This file is part of the broadway/snapshotting package.
 *
 *  (c) Qandidate.com <opensource@qandidate.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\Snapshot\Snapshotter;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\Snapshotting\Snapshot\Snapshot;
use Broadway\Snapshotting\Snapshot\SnapshotRepository;

class SynchronousSnapshotterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SnapshotRepository
     */
    private $repository;

    /**
     * @var SynchronousSnapshotter
     */
    private $snapshotter;

    /**
     * @test
     */
    public function it_persists_directly_to_SnapshotRepository()
    {
        $aggregate = new MyAggregate();

        $this->repository
            ->save(new Snapshot($aggregate))
            ->shouldBeCalled();

        $this->snapshotter->takeSnapshot($aggregate);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->repository = $this->prophesize(SnapshotRepository::class);
        $this->snapshotter = new SynchronousSnapshotter($this->repository->reveal());
    }
}

final class MyAggregate extends EventSourcedAggregateRoot
{
    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return 42;
    }
}