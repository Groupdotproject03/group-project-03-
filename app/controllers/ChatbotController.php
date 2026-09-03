<?php
/**
 * Chatbot Controller
 */

class ChatbotController extends Controller {
    private $chatbotModel;

    public function __construct() {
        $this->chatbotModel = new ChatbotModel();
    }

    public function response() {
        $question = $this->post('question', '');
        $answer = $this->chatbotModel->matchResponse($question);
        $this->json(['answer' => $answer]);
    }

    public function widget() {
        $this->view('chatbot/chatbot_widget');
    }
}
