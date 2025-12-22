<?php

namespace app\Controllers;

use config\DBConnection;
use app\Models\BookingModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class BookingController
{
    private $BookingModel;

    public function __construct()
    {
        $db = new DBConnection();
        $this->BookingModel = new BookingModel($db);
    }

    public function getCourts()
    {
        $courts = $this->BookingModel->getCourts();
        echo $GLOBALS['templates']->render('Courts', ['courts' => $courts]);
    }

    public function addCourt()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $courtType = trim($_POST['courtType'] ?? '');
            $capacity = trim($_POST['capacity'] ?? '');
            $amount = trim($_POST['amount'] ?? '');

            $court = $this->BookingModel->addCourt($courtType, $capacity, $amount);
            if ($court) {
                $_SESSION['success'][] = 'Court added successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to add court. Please try again.';
            }

            header('Location: /courts');
            exit;
        }
    }

    public function getCourt($courtId)
    {
        $court = $this->BookingModel->getCourtById($courtId);
        echo $GLOBALS['templates']->render('UpdateCourt', ['court' => $court]);
    }

    public function viewCourt($courtId)
    {
        $court = $this->BookingModel->getCourtById($courtId);
        echo $GLOBALS['templates']->render('ViewCourt', ['court' => $court]);
    }

    public function updateCourt($courtId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $courtType = trim($_POST['courtType'] ?? '');
            $capacity = trim($_POST['capacity'] ?? '');
            $amount = trim($_POST['amount'] ?? '');

            $updated = $this->BookingModel->updateCourt($courtId, $courtType, $capacity, $amount);
            if ($updated) {
                $_SESSION['success'][] = 'Court updated successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to update court. Please try again.';
            }

            header('Location: /courts');
            exit;
        }
    }

    public function deleteCourt($courtId)
    {
        $userId = $_SESSION['user_id'] ?? 0;

        $deleted = $this->BookingModel->deleteCourt($courtId, $userId);
        if ($deleted) {
            $_SESSION['success'][] = 'Court deleted successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to delete court. Please try again.';
        }

        header('Location: /courts');
        exit;
    }

    public function getMembers()
    {
        $members = $this->BookingModel->getMembers();
        echo $GLOBALS['templates']->render('Members', ['members' => $members]);
    }

    public function viewMember($memberId)
    {
        $member = $this->BookingModel->getMemberById($memberId);
        echo $GLOBALS['templates']->render('ViewMember', ['member' => $member]);
    }

    public function getSchedules()
    {
        $schedules = $this->BookingModel->getSchedules();
        echo $GLOBALS['templates']->render('Schedules', ['schedules' => $schedules]);
    }

    public function getSchedulesArchived()
    {
        $schedules = $this->BookingModel->getSchedulesArchived();
        echo $GLOBALS['templates']->render('BookingArchive', ['schedules' => $schedules]);
    }

    public function viewSchedule($scheduleId)
    {
        $schedule = $this->BookingModel->getScheduleById($scheduleId);
        echo $GLOBALS['templates']->render('ViewSchedule', ['schedule' => $schedule]);
    }

    public function viewArchive($scheduleId)
    {
        $schedule = $this->BookingModel->getScheduleById($scheduleId);
        echo $GLOBALS['templates']->render('viewBookingArchive', ['schedule' => $schedule]);
    }

    public function confirmSchedule($scheduleId)
    {
        $bookingInfo = $this->BookingModel->getScheduleById($scheduleId);
        if ($bookingInfo) {
            $totalAmount = floatval($bookingInfo['total_amount']);
            $remainingAmount = number_format($totalAmount / 2, 2);

            $confirmed = $this->BookingModel->confirmSchedule($scheduleId, $remainingAmount);

            if ($confirmed) {
                $emailSent = $this->sendBookingConfirmationEmail(
                    $bookingInfo['email'],
                    $bookingInfo['first_name'] . ' ' . $bookingInfo['last_name'],
                    $bookingInfo['court_name'],
                    $bookingInfo['date'],
                    $bookingInfo['start_time'],
                    $bookingInfo['end_time'],
                    $bookingInfo['total_amount']
                );

                // Notify admin if email fails
                if (!$emailSent) {
                    $_SESSION['warning'][] = 'Booking confirmed, but email notification failed to send.';
                }
                $_SESSION['success'][] = 'Booking confirmed successfully and email notification sent.';
            } else {
                $_SESSION['danger'][] = 'Booking confirmed, but schedule details could not be loaded.';
            }
        } else {
            $_SESSION['danger'][] = 'Failed to confirm booking. Please try again.';
        }

        header('Location: /viewSchedule/' . $scheduleId);
        exit;
    }

    public function cancelSchedule($scheduleId)
    {
        $bookingInfo = $this->BookingModel->getScheduleById($scheduleId);
        if ($bookingInfo) {
            $canceled = $this->BookingModel->cancelSchedule($scheduleId);
            if ($canceled) {
                $emailSent = $this->sendBookingCancellationEmail(
                    $bookingInfo['email'],
                    $bookingInfo['first_name'] . ' ' . $bookingInfo['last_name'],
                    $bookingInfo['court_name'],
                    $bookingInfo['date'],
                    $bookingInfo['start_time'],
                    $bookingInfo['end_time'],
                    $bookingInfo['total_amount']
                );

                if (!$emailSent) {
                    $_SESSION['warning'][] = 'Booking cancelled, but email notification failed to send.';
                }
                $_SESSION['success'][] = 'Booking cancelled successfully and email notification sent.';
            } else {
                $_SESSION['danger'][] = 'Failed to cancel booking. Please try again.';
            }
        } else {
            $_SESSION['danger'][] = 'Failed to cancel booking. Please try again.';
        }

        header('Location: /viewSchedule/' . $scheduleId);
        exit;
    }

    public function undoCancelSchedule($scheduleId)
    {
        $bookingInfo = $this->BookingModel->getScheduleById($scheduleId);
        if ($bookingInfo) {
            $undoCancel = $this->BookingModel->undoCancelSchedule($scheduleId);
            if ($undoCancel) {
                $emailSent = $this->sendBookingUndoCancellationEmail(
                    $bookingInfo['email'],
                    $bookingInfo['first_name'] . ' ' . $bookingInfo['last_name'],
                    $bookingInfo['court_name'],
                    $bookingInfo['date'],
                    $bookingInfo['start_time'],
                    $bookingInfo['end_time'],
                    $bookingInfo['total_amount']
                );

                if (!$emailSent) {
                    $_SESSION['warning'][] = 'Booking restoration successful, but email notification failed to send.';
                }
                $_SESSION['success'][] = 'Booking restoration successful and email notification sent.';
            } else {
                $_SESSION['danger'][] = 'Failed to undo booking cancellation. Please try again.';
            }
        } else {
            $_SESSION['danger'][] = 'Failed to undo booking cancellation. Please try again.';
        }

        header('Location: /viewSchedule/' . $scheduleId);
        exit;
    }

    private function sendBookingConfirmationEmail($email, $name, $courtType, $date, $startTime, $endTime, $totalAmount)
    {
        $subject = "Court Booking Confirmation - " . $courtType . " Court";

        $formattedDate = date('F j, Y', strtotime($date));
        $formattedStart = date('g:i A', strtotime($startTime));
        $formattedEnd = date('g:i A', strtotime($endTime));
        $formattedAmount = ($totalAmount == 0.00) ? 'Paid' : '₱' . number_format($totalAmount, 2);
        $appName = $_ENV['APP_NAME'] ?? '';

        $remainingAmountHtml = '';
        if ($totalAmount != 0.00) {
            $formattedRemainingAmount = '₱' . number_format($totalAmount / 2, 2);
            $remainingAmountHtml = "<p style='color:red;'><strong>Remaining Amount:</strong> $formattedRemainingAmount</p>";
        }

        $body = "
            <div style='font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px;'>
                <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
                <div style='background-color: #28a745;color:white;text-align:center;padding:20px;'>
                <h2 style='margin:0;'>Court Booking Confirmed</h2>
                </div>
                <div style='padding:25px;'>
                <p>Hi <strong>$name</strong>,</p>
                <p>Great news! Your court booking has been <strong>confirmed</strong>. Your reservation is now active.</p>

                <div style='background-color:#f9fafc;padding:15px;border-radius:6px;margin:15px 0;'>
                <p><strong>Court Type:</strong> $courtType </p>
                <p><strong>Date:</strong> $formattedDate </p> 
                <p><strong>Time:</strong> $formattedStart - $formattedEnd</p>
                <p><strong>Total Amount:</strong> $formattedAmount</p>
                $remainingAmountHtml
                </div>

                <p>Please arrive a few minutes early to the venue. If you have any questions, feel free to contact us.</p>
                <p>Thank you for choosing <strong>$appName</strong>!</p>

                <hr style='border:none;border-top:1px solid #ddd;margin:20px 0;'>

                <p style='font-size:13px;color:#777;text-align:center;'>This is an automated message, please do not reply.<br>
                &copy; " . date('Y') . " Lugod Square. All rights reserved.</p>
                </div>
                </div>
            </div>
            ";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';

            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email error: " . $mail->ErrorInfo);
            return false;
        }
    }

    private function sendBookingCancellationEmail($email, $name, $courtType, $date, $startTime, $endTime, $totalAmount)
    {
        $subject = "Court Booking Cancellation - " . $courtType . " Court";

        $formattedDate = date('F j, Y', strtotime($date));
        $formattedStart = date('g:i A', strtotime($startTime));
        $formattedEnd = date('g:i A', strtotime($endTime));
        $formattedAmount = ($totalAmount == 0.00) ? 'Paid' : '₱' . number_format($totalAmount, 2);
        $appName = $_ENV['APP_NAME'] ?? '';

        $body = "
            <div style='font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px;'>
            <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
            <div style='background-color: #dc3545;color:white;text-align:center;padding:20px;'>
            <h2 style='margin:0;'>Court Booking Cancelled</h2>
            </div>
            <div style='padding:25px;'>
            <p>Hi <strong>$name</strong>,</p>
            <p>We regret to inform you that your court booking has been <strong>cancelled</strong>. Your reservation is no longer active.</p>

            <p style='color:#666;line-height:1.6;'>We sincerely apologize for any inconvenience this may cause. Due to unforeseen circumstances beyond our control, we were unable to maintain this reservation. We understand how disappointing this can be, and we truly value your business. We encourage you to rebook at your earliest convenience, and we'll do our best to accommodate your preferred time slot.</p>

            <div style='background-color:#f9fafc;padding:15px;border-radius:6px;margin:15px 0;'>
            <p><strong>Court Type:</strong> $courtType </p>
            <p><strong>Date:</strong> $formattedDate </p> 
            <p><strong>Time:</strong> $formattedStart - $formattedEnd</p>
            <p><strong>Total Amount:</strong> $formattedAmount</p>
            </div>

            <div style='background-color:#fff3cd;padding:15px;border-radius:6px;margin:15px 0;border-left:4px solid #ffc107;'>
            <p><strong>Refund Information:</strong></p>
            <p>Our administration team will contact you shortly regarding any applicable refunds or adjustments to your account.</p>
            </div>

            <p>If you have any questions or wish to rebook, please feel free to contact us.</p>
            <p>Thank you for using <strong>$appName</strong>!</p>

            <hr style='border:none;border-top:1px solid #ddd;margin:20px 0;'>

            <p style='font-size:13px;color:#777;text-align:center;'>This is an automated message, please do not reply.<br>
            &copy; " . date('Y') . " Lugod Square. All rights reserved.</p>
            </div>
            </div>
            </div>
            ";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';

            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email error: " . $mail->ErrorInfo);
            return false;
        }
    }

    private function sendBookingUndoCancellationEmail($email, $name, $courtType, $date, $startTime, $endTime, $totalAmount)
    {
        $subject = "Court Booking Restoration - " . $courtType . " Court";

        $formattedDate = date('F j, Y', strtotime($date));
        $formattedStart = date('g:i A', strtotime($startTime));
        $formattedEnd = date('g:i A', strtotime($endTime));
        $formattedAmount = ($totalAmount == 0.00) ? 'Paid' : '₱' . number_format($totalAmount, 2);
        $appName = $_ENV['APP_NAME'] ?? '';

        $body = "
            <div style='font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px;'>
            <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
            <div style='background-color: #17a2b8;color:white;text-align:center;padding:20px;'>
            <h2 style='margin:0;'>Court Booking Restoration</h2>
            </div>
            <div style='padding:25px;'>
            <p>Hi <strong>$name</strong>,</p>
            <p>We sincerely apologize! The cancellation of your court booking was made in <strong>error</strong>. Our team has restored your reservation and it is <strong>pending confirmation</strong>.</p>

            <p style='color:#666;line-height:1.6;'>We understand the inconvenience this may have caused and we truly apologize. Our administration team will review your booking request again to ensure everything is correct. We are committed to providing you with the best service possible.</p>

            <div style='background-color:#f9fafc;padding:15px;border-radius:6px;margin:15px 0;'>
            <p><strong>Court Type:</strong> $courtType </p>
            <p><strong>Date:</strong> $formattedDate </p> 
            <p><strong>Time:</strong> $formattedStart - $formattedEnd</p>
            <p><strong>Total Amount:</strong> $formattedAmount</p>
            </div>

            <div style='background-color:#fff3cd;padding:15px;border-radius:6px;margin:15px 0;border-left:4px solid #ffc107;'>
            <p><strong>Status:</strong> Your booking is now <strong>pending confirmation</strong>.</p>
            <p>Our team will contact you shortly to confirm all details and ensure your booking is properly set up.</p>
            </div>

            <p>If you have any questions or concerns, please feel free to contact us.</p>
            <p>Thank you for your patience and for using <strong>$appName</strong>!</p>

            <hr style='border:none;border-top:1px solid #ddd;margin:20px 0;'>

            <p style='font-size:13px;color:#777;text-align:center;'>This is an automated message, please do not reply.<br>
            &copy; " . date('Y') . " Lugod Square. All rights reserved.</p>
            </div>
            </div>
            </div>
            ";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';

            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
