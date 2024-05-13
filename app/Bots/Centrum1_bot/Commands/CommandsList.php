<?php
namespace App\Bots\Centrum1_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static protected $commands = [
        'admin'     => [
            // AdminCommands\StartCommand::class,
            // AdminCommands\DefaultCommand::class,
            // AdminCommands\HelpCommand::class,
            // AdminCommands\ReferalCommand::class,
            // AdminCommands\GetReferalCommand::class,
        ],
        'user'      => [
            
            
            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
            UserCommands\HelpCommand::class,
            UserCommands\DefaultCommand::class,
            UserCommands\ContactManager::class,
            UserCommands\SendContact::class,
            UserCommands\AssignTag::class,
            UserCommands\DataIsSend::class,

            
            UserCommands\CalculateInsurance\CalculateInsurance::class,
            UserCommands\CalculateInsurance\CalculateInsuranceAgain::class,
            UserCommands\CalculateInsurance\CalculateInsuranceNotifyLater::class,
            UserCommands\CalculateInsurance\OrderInsuranceNotifyLater::class,

            //CalculateByCompany
            UserCommands\CalculateInsurance\CalculateByCompany\CalculateByCompany::class,
            
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
            UserCommands\CalculateInsurance\SaveBirth::class,
            UserCommands\CalculateInsurance\StartDate::class,
            UserCommands\CalculateInsurance\AwaitStartDate::class,
            // UserCommands\CalculateInsurance\SaveStartDate::class,

            // UserCommands\CalculateInsurance\EndDate::class,
            // UserCommands\CalculateInsurance\AwaitEndDate::class,
            // UserCommands\CalculateInsurance\SaveEndDate::class,
            UserCommands\CalculateInsurance\Applying::class,

            UserCommands\CalculateInsurance\Shengen::class,

            UserCommands\CalculateInsurance\Type::class,
            UserCommands\CalculateInsurance\Calculate::class,
            UserCommands\CalculateInsurance\BuyInsurance::class,
            UserCommands\CalculateInsurance\InsuranceInfo::class,
            UserCommands\CalculateInsurance\OppositionInsurance::class,

            // Questionnaire
            UserCommands\Questionnaire\Answer::class,
            UserCommands\Questionnaire\Question::class,

            // Profile
            UserCommands\Profile\Profile::class,

            UserCommands\Profile\FirstName::class,
            UserCommands\Profile\AwaitFirstName::class,

            UserCommands\Profile\LastName::class,
            UserCommands\Profile\AwaitLastName::class,

            UserCommands\Profile\Phone::class,
            UserCommands\Profile\AwaitPhone::class,

            // LeaveContact
            UserCommands\LeaveContact\LeaveContact::class,

            UserCommands\LeaveContact\FirstName::class,
            UserCommands\LeaveContact\AwaitFirstName::class,

            UserCommands\LeaveContact\LastName::class,
            UserCommands\LeaveContact\AwaitLastName::class,

            UserCommands\LeaveContact\Phone::class,
            UserCommands\LeaveContact\AwaitPhone::class,

            UserCommands\LeaveContact\Situation::class,
            UserCommands\LeaveContact\AwaitSituation::class,

            UserCommands\LeaveContact\Send::class,

            // Calculate bank
            UserCommands\CalculateBank\CalculateAmount::class,
            UserCommands\CalculateBank\EndOfVisa::class,
            UserCommands\CalculateBank\Applying::class,
            UserCommands\CalculateBank\Deadline::class,
            UserCommands\CalculateBank\Calculate::class,

            // VisaAndResidentPermit
            UserCommands\VisaAndResidentPermit\VisaAndResidentPermit::class,

            // Student
            UserCommands\VisaAndResidentPermit\Student\Student::class,
            UserCommands\VisaAndResidentPermit\Student\ImGettingDiploma::class,
            UserCommands\VisaAndResidentPermit\Student\IveBeenExpelled::class,
            UserCommands\VisaAndResidentPermit\Student\IWantStudentVisa::class,
            UserCommands\VisaAndResidentPermit\Student\IWantChangePurposeOfStaying::class,
            UserCommands\VisaAndResidentPermit\Student\IWantExtendMyStudyVisa::class,
            UserCommands\VisaAndResidentPermit\Student\Nostrification::class,

            // Work
            UserCommands\VisaAndResidentPermit\Work\Work::class,
            UserCommands\VisaAndResidentPermit\Work\IWantWorkVisa::class,
            UserCommands\VisaAndResidentPermit\Work\IveBeenFired::class,
            UserCommands\VisaAndResidentPermit\Work\IWantExtendMyWorkVisa::class,
            UserCommands\VisaAndResidentPermit\Work\IWantChangeEmployer::class,

            // Business
            UserCommands\VisaAndResidentPermit\Business\Business::class,
            UserCommands\VisaAndResidentPermit\Business\IWantBusinessVisa::class,
            UserCommands\VisaAndResidentPermit\Business\IWantOpenBusiness::class,
            UserCommands\VisaAndResidentPermit\Business\IWantExtendMyBusinessVisa::class,

            // Family
            UserCommands\VisaAndResidentPermit\Family\Family::class,
            UserCommands\VisaAndResidentPermit\Family\RegistrationOfMarriage::class,
            UserCommands\VisaAndResidentPermit\Family\IWantFamilyVisa::class,
            UserCommands\VisaAndResidentPermit\Family\IWantExtendMyFamilyVisa::class,
            UserCommands\VisaAndResidentPermit\Family\IWantTemporaryResidence::class,
            UserCommands\VisaAndResidentPermit\Family\IWantExtendMyTemporaryResidence::class,

            // AfterUniversityVisa
            UserCommands\VisaAndResidentPermit\AfterUniversityVisa\AfterUniversityVisa::class,
            UserCommands\VisaAndResidentPermit\AfterUniversityVisa\IGotAfterUniversityVisa::class,
            UserCommands\VisaAndResidentPermit\AfterUniversityVisa\IWantAfterUniversityVisa::class,

            // LanguageCourses
            UserCommands\VisaAndResidentPermit\LanguageCourses\LanguageCourses::class,
            UserCommands\VisaAndResidentPermit\LanguageCourses\LanguageCourses2324::class,
            UserCommands\VisaAndResidentPermit\LanguageCourses\LanguageCourses99::class,
            UserCommands\VisaAndResidentPermit\LanguageCourses\PrivatUniversity::class,

            // Adress
            UserCommands\Adress::class,

            // PMJ
            UserCommands\PMJ\PMJ::class,
            UserCommands\PMJ\IWantPMJ::class,
            UserCommands\PMJ\PMJExperience::class,
            UserCommands\PMJ\RetentPMJWhenLeaving::class,
            UserCommands\PMJ\TurnkeyPMJ::class,

            // Citizenship
            UserCommands\Citizenship::class,

            // MyApplicationTakingLongTimeToProcess
            UserCommands\MyApplicationTakingLongTimeToProcess::class,

            // UserCommands\VisaAndResidentPermit\Student\ElseQuestion::class,
            // UserCommands\VisaAndResidentPermit\Student\Work::class,
            // UserCommands\VisaAndResidentPermit\Student\Business::class,
            // UserCommands\VisaAndResidentPermit\Student\Family::class,
            // UserCommands\VisaAndResidentPermit\Student\AfterUniversityVisa::class,
            // UserCommands\VisaAndResidentPermit\Student\LanguageCourses::class,
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
