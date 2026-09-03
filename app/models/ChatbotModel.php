<?php
/**
 * Chatbot Model
 */

class ChatbotModel {
    private $rules = [
        ['keywords' => ['emergency', 'ambulance'],
         'answer'   => "For an ambulance, go to Dashboard → 🚑 Emergency and tap Request Ambulance. If this is a life-threatening emergency, please call your local emergency number immediately."],

        ['keywords' => ['appointment', 'book doctor', 'book appointment'],
         'answer'   => "You can book a doctor from Dashboard → 📅 Book Doctor, and view your bookings under 📋 My Appointments."],

        ['keywords' => ['trainer'],
         'answer'   => "You can book a fitness trainer from Dashboard → 🏋️ Book Trainer."],

        ['keywords' => ['consultation', 'meeting link', 'video call', 'meeting'],
         'answer'   => "Open Dashboard → 🩺 Online Consultation. The chat box and meeting link unlock 15 minutes before your scheduled appointment time."],

        ['keywords' => ['chat support', 'human', 'agent', 'staff', 'talk to someone'],
         'answer'   => "You can reach our 24/7 Chat Support team from Dashboard → 💬 Chat Support for live help from clinic staff."],

        ['keywords' => ['report', 'sample', 'test'],
         'answer'   => "You can request a sample collection from Dashboard → 🧪 Request Sample Collection, and download your health report from the button at the top of your dashboard."],

        ['keywords' => ['reminder'],
         'answer'   => "You can set medication or activity reminders from Dashboard → ⏰ Set Reminder."],

        ['keywords' => ['password', 'login', 'account'],
         'answer'   => "For login or account issues, please use Chat Support and our staff will help you directly."],

        ['keywords' => ['score', 'health score'],
         'answer'   => "Your average health score is shown at the top of your dashboard and is based on your recent daily logs."],

        ['keywords' => ['hi', 'hello', 'hey'],
         'answer'   => "Hello! How can I help you today? You can ask me about appointments, consultations, chat support, or ambulance requests."],

        ['keywords' => ['thank'],
         'answer'   => "You're welcome! Is there anything else I can help with?"],
    ];

    public function matchResponse($question) {
        $q = strtolower(trim($question ?? ''));
        if (empty($q)) {
            return "Hi! I'm your HealthChecker assistant. Ask me about appointments, ambulance requests, chat support, or your reports.";
        }

        foreach ($this->rules as $rule) {
            foreach ($rule['keywords'] as $kw) {
                if (strpos($q, $kw) !== false) {
                    return $rule['answer'];
                }
            }
        }

        return "I'm not fully sure about that yet, but our 24/7 Chat Support staff can help — open Dashboard → 💬 Chat Support to talk to a real person.";
    }
}
