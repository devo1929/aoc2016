<?php
/* Day 1: No Time for a Taxicab */
$input = array_map(function ($i) {
    preg_match('/(R|L)(\d+)/i', $i, $matched);
    return [
        'turn' => $matched[1],
        'blocks' => intval($matched[2]),
    ];
}, explode(', ', file_get_contents('./inputs/day1.txt')));

function getDirection($current, $turn)
{
    switch ($current) {
        case 'N':
            return $turn == 'R' ? 'E' : 'W';
        case 'S':
            return $turn == 'R' ? 'W' : 'E';
        case 'E':
            return $turn == 'R' ? 'S' : 'N';
        case 'W':
            return $turn == 'R' ? 'N' : 'S';
    }
}

function walk(&$x, &$y, &$a, $blocks, &$history, &$firstRepeat)
{
    if ($firstRepeat) {
        // first repeat already found, no point in walking, just add
        $a += $blocks;
        return;
    }
    for ($i = 0; $i < abs($blocks); $i++) {
        $a += $blocks > 0 ? 1 : -1;
        $position = "$x,$y";
        if (!$firstRepeat && in_array($position, $history)) {
            $firstRepeat = [$x, $y];
        }
        $history[] = $position;
    }
}

/**
 * Part 1
 *
 * Santa's sleigh uses a very high-precision clock to guide its movements, and the clock's oscillator is regulated by stars. Unfortunately, the stars have been stolen... by the Easter Bunny. To save Christmas, Santa needs you to retrieve all fifty stars by December 25th.
 *
 * Collect stars by solving puzzles. Two puzzles will be made available on each day in the advent calendar; the second puzzle is unlocked when you complete the first. Each puzzle grants one star. Good luck!
 *
 * You're airdropped near Easter Bunny Headquarters in a city somewhere. "Near", unfortunately, is as close as you can get - the instructions on the Easter Bunny Recruiting Document the Elves intercepted start here, and nobody had time to work them out further.
 *
 * The Document indicates that you should start at the given coordinates (where you just landed) and face North. Then, follow the provided sequence: either turn left (L) or right (R) 90 degrees, then walk forward the given number of blocks, ending at a new intersection.
 *
 * There's no time to follow such ridiculous instructions on foot, though, so you take a moment and work out the destination. Given that you can only walk on the street grid of the city, how far is the shortest path to the destination?
 *
 * For example:
 *
 * Following R2, L3 leaves you 2 blocks East and 3 blocks North, or 5 blocks away.
 * R2, R2, R2 leaves you 2 blocks due South of your starting position, which is 2 blocks away.
 * R5, L5, R5, R3 leaves you 12 blocks away.
 *
 * How many blocks away is Easter Bunny HQ?
 *
 * Your puzzle answer was 230.
 *
 ********************************************************************
 * 
 * Part 2
 *
 * Then, you notice the instructions continue on the back of the Recruiting Document. Easter Bunny HQ is actually at the first location you visit twice.
 *
 * For example, if your instructions are R8, R4, R4, R8, the first location you visit twice is 4 blocks away, due East.
 *
 * How many blocks away is the first location you visit twice?
 *
 * Your puzzle answer was 154.
 */
function part1and2($input)
{
    $x = $y = 0;
    $direction = 'N';
    $history = [];
    $firstRepeat = null;
    for ($i = 0; $i < count($input); $i++) {
        $instruction = $input[$i];
        $direction = getDirection($direction, $instruction['turn']);
        $blocks = $instruction['blocks'];
        switch ($direction) {
            case 'N':
                walk($x, $y, $y, $blocks, $history, $firstRepeat);
                break;
            case 'S':
                walk($x, $y, $y, -$blocks, $history, $firstRepeat);
                break;
            case 'E':
                walk($x, $y, $x, $blocks, $history, $firstRepeat);
                break;
            case 'W':
                walk($x, $y, $x, -$blocks, $history, $firstRepeat);
                break;
        }
    }
    echo sprintf("final position: %s blocks away\n", abs($x) + abs($y));
    echo sprintf("first repeat: %s blocks away\n", abs($firstRepeat[0]) + abs($firstRepeat[1]));
}

part1($input);