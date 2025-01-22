<?php

namespace App\Controllers;

use App\Models\CronModel;

class CronContoller extends BaseController {

    protected $cronModel;

    public function sendReminderEmails() {
        $testDrives = $this->cronModel->getUpcomingTestDrives();

        foreach ($testDrives as $testDrive) {
            $to = $testDrive['customer_email'];
            $toName = $testDrive['customer_name'];
            $subject = "Reminder: Upcoming Test Drive Scheduled";
            $message = "
                Dear {$testDrive['customer_name']},
                
                This is a friendly reminder about your upcoming test drive:
                
                - **Date**: {$testDrive['dateOfVisit']}
                - **Time Slot**: " . $this->mapTimeSlot($testDrive['timeOfVisit']) . "
                - **Comments**: {$testDrive['comments']}
                
                If you have any questions or need to reschedule, please contact us.
                
                Best regards,
                Your Dealership Team";

            // Use your email library to send the email
            
            $emailSent = sendEmail($to, $toName, $subject, $message, );

            if ($emailSent) {
                echo "Reminder sent to {$testDrive['customer_name']} ({$to})\n";
            } else {
                echo "Failed to send reminder to {$testDrive['customer_name']} ({$to})\n";
            }
        }
    }

    // Helper function to map time slots
    private function mapTimeSlot($timeOfVisit) {
        $slots = [
            '1' => 'Morning (11 AM - 1 PM)',
            '2' => 'Afternoon (1 PM - 4 PM)',
            '3' => 'Evening (4 PM - 8 PM)'
        ];
        return $slots[$timeOfVisit] ?? 'Unknown Slot';
    }
}
