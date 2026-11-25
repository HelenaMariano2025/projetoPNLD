<?php

use PHPUnit\Framework\TestCase;

final class QualityGateDemoTest extends TestCase
{
    public function testQualityGateDetectaFalhaProposital(): void
    {
        self::assertSame(1, 2);
    }
}
