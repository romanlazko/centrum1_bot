<?php
namespace App\Bots\Centrum1_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static protected $commands = [
        'admin'     => [
            AdminCommands\StartCommand::class,
            AdminCommands\DefaultCommand::class,
            AdminCommands\HelpCommand::class,
            AdminCommands\ReferalCommand::class,
            AdminCommands\GetReferalCommand::class,
        ],
        'user'      => [
            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
            UserCommands\HelpCommand::class,
            UserCommands\DefaultCommand::class,
            UserCommands\ContactManager::class,
            UserCommands\SendContact::class,

            
            UserCommands\CalculateInsurance\CalculateInsurance::class,
            // LowestCost
            UserCommands\CalculateInsurance\LowestCost\LowestCost::class,
            UserCommands\CalculateInsurance\LowestCost\CalculateLowestCost::class,

            // PriceAndQuality
            UserCommands\CalculateInsurance\PriceAndQuality\PriceAndQuality::class,
            UserCommands\CalculateInsurance\PriceAndQuality\CalculatePriceAndQuality::class,

            // BetterQuality
            UserCommands\CalculateInsurance\BetterQuality\BetterQuality::class,
            UserCommands\CalculateInsurance\BetterQuality\CalculateBetterQuality::class,

            // ContinuingTreatment
            UserCommands\CalculateInsurance\ContinuingTreatment\ContinuingTreatment::class,
            UserCommands\CalculateInsurance\ContinuingTreatment\SaveInsurance::class,
            UserCommands\CalculateInsurance\ContinuingTreatment\CalculateContinuingTreatment::class,

            UserCommands\CalculateInsurance\AwaitBirth::class,
            UserCommands\CalculateInsurance\BirthCommand::class,
            UserCommands\CalculateInsurance\StartDate::class,
            UserCommands\CalculateInsurance\AwaitStartDate::class,
            UserCommands\CalculateInsurance\SaveStartDate::class,

            UserCommands\CalculateInsurance\EndDate::class,
            UserCommands\CalculateInsurance\AwaitEndDate::class,
            UserCommands\CalculateInsurance\SaveEndDate::class,

            UserCommands\CalculateInsurance\Shengen::class,

            UserCommands\CalculateInsurance\Type::class,
            UserCommands\CalculateInsurance\Calculate::class,
            UserCommands\CalculateInsurance\InsuranceInfo::class,
        ],
        
    ];

    static public function getCommandsList(?string $auth)
    {
        return array_merge(
            (self::$commands[$auth] ?? []), 
            (self::$default_commands[$auth] ?? [])
        ) 
        ?? self::getCommandsList('default');
    }

    static public function getAllCommandsList()
    {
        foreach (self::$commands as $auth => $commands) {
            $commands_list[$auth] = self::getCommandsList($auth);
        }
        return $commands_list;
    }
}
