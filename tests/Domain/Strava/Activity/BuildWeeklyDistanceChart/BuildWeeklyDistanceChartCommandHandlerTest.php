<?php

namespace App\Tests\Domain\Strava\Activity\BuildWeeklyDistanceChart;

use App\Domain\Strava\Activity\BuildWeeklyDistanceChart\BuildWeeklyDistanceChart;
use App\Infrastructure\CQRS\CommandBus;
use App\Tests\DatabaseTestCase;
use App\Tests\ProvideTestData;
use League\Flysystem\FilesystemOperator;
use Spatie\Snapshots\MatchesSnapshots;

class BuildWeeklyDistanceChartCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;
    use ProvideTestData;

    private CommandBus $commandBus;

    public function testHandle(): void
    {
        $this->provideFullTestSet();

        $this->commandBus->dispatch(new BuildWeeklyDistanceChart());

        /** @var \App\Tests\SpyFileSystem $fileSystem */
        $fileSystem = $this->getContainer()->get(FilesystemOperator::class);
        $this->assertMatchesJsonSnapshot($fileSystem->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->getContainer()->get(CommandBus::class);
    }
}
