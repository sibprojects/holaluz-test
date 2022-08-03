<?php

namespace App\Tests\Console;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReadCommandTest extends KernelTestCase
{
    /**
     * @var CommandTester
     */
    private $application;

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $this->application = new Application($kernel);
    }

    /**
     * @dataProvider ordersProvider
     */
    public function testCoffeeMachineReturnsTheExpectedOutput(string $fileName, string $expectedResult): void {
        $command = $this->application->find('app:read');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),

            // pass arguments to the helper
            'file' => $fileName,
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertSame($expectedResult, $output);
    }

    public function ordersProvider(): array
    {
        return [
            [
                'tests/data/no_exists_file.csv',
                'File not found!' . PHP_EOL,
            ],
            [
                'tests/test-data/2016-readings-correct.csv',
                'All reading in file is correct!' . PHP_EOL,
            ],
            [
                'tests/test-data/2016-readings.csv',
                '+---------------+---------+------------+--------+' . PHP_EOL .
                '| Client        | Month   | Suspicious | Median |' . PHP_EOL .
                '+---------------+---------+------------+--------+' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-03 | 44055      | -111   |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-04 | 40953      | -259   |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-06 | 41216      | -169   |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-08 | 43324      | -114   |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-09 | 3564       | -2149  |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-10 | 44459      | 2008   |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-11 | 42997      | -175   |' . PHP_EOL .
                '| 583ef6329d7b9 | 2016-12 | 42600      | -120   |' . PHP_EOL .
                '+---------------+---------+------------+--------+' . PHP_EOL,
            ],
            [
                'tests/test-data/2016-readings.xml',
                '+---------------+---------+------------+--------+' . PHP_EOL .
                '| Client        | Month   | Suspicious | Median |' . PHP_EOL .
                '+---------------+---------+------------+--------+' . PHP_EOL .
                '| 583ef6329df6b | 2016-02 | 36537      | -156   |' . PHP_EOL .
                '| 583ef6329df6b | 2016-03 | 36430      | -108   |' . PHP_EOL .
                '| 583ef6329df6b | 2016-04 | 36622      | -84    |' . PHP_EOL .
                '| 583ef6329df6b | 2016-06 | 35382      | -294   |' . PHP_EOL .
                '| 583ef6329df6b | 2016-07 | 37970      | 111    |' . PHP_EOL .
                '| 583ef6329df6b | 2016-08 | 38463      | -59    |' . PHP_EOL .
                '| 583ef6329df6b | 2016-09 | 35252      | -360   |' . PHP_EOL .
                '| 583ef6329df6b | 2016-10 | 35704      | -63    |' . PHP_EOL .
                '| 583ef6329df6b | 2016-11 | 38220      | 105    |' . PHP_EOL .
                '| 583ef6329df6b | 2016-12 | 36688      | -224   |' . PHP_EOL .
                '+---------------+---------+------------+--------+' . PHP_EOL,
            ],
        ];
    }
}
