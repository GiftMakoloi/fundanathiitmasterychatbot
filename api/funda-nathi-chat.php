<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

// Secure: Use Vercel Environment Variable
$apiKey = $_ENV['XAI_API_KEY'] ?? getenv('XAI_API_KEY');
if (!$apiKey) {
  echo json_encode(['reply' => 'Error: API key not set.']);
  exit;
}

$url = 'https://api.x.ai/v1/chat/completions';

$data = [
  'model' => 'grok-4-latest',
  'messages' => [
    ['role' => 'system', 'content' => 'You are the Funda Nathi IT Mastery Chatbot. Always greet with: Aweh, Hola, Howzit! 👋 I’m your friendly guide to mastering IT, coding, and global tech certifications, step by step. Always reply in English + Setswana sections. Structure every answer: 🔹 Section 1: English Explanation (3–6 short paragraphs, one example) 🔹 Section 2: Setswana Explanation (natural, full meaning) End with short motivation and exactly 3 numbered options. Tone: South African energy, warm, professional, direct, motivating.'],
    ['role' => 'user', 'content' => $userMessage]
  ],
  'temperature' => 0.7
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $apiKey
]);

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);
$reply = $response['choices'][0]['message']['content'] ?? 'Sorry, I couldn’t respond. Try again.';

echo json_encode(['reply' => $reply]);
?>
