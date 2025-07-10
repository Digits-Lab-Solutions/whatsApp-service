# Laravel WhatsApp Messaging Service (MsgUp API)

This package provides a reusable Laravel service to send WhatsApp messages via MsgUp using template-based messaging. It supports sending:

- Plain body text messages
- Messages with a document (PDF/image/etc.) header
- Multiple dynamic templates with easy customization

---

## ✅ Installation

Step 1: Add the repository (if private)

<pre> 
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/Digits-Lab-Solutions/whatsapp-service.git"
  }
]
</pre> 

Step 2: Require the package via Composer
<pre> 
composer require digitslab/whatsapp-service:^1.0

php artisan vendor:publish --tag=config
</pre> 

Step 3: Set the following in your .env file:
<pre> 
WHATSAPP_API_TOKEN=your-msgup-api-token
WHATSAPP_API_URL=https://msgup.in/api/wpbox/sendtemplatemessage
</pre> 


# Controller Example
<pre> 
use Illuminate\Http\Request;
use Digitslab\WhatsAppService\WhatsAppService;

  public function sendWhatsappMsg(Request $request)
  {
      $validated = $request->validate([
          'phone' => 'required|string',
          'template_name' => 'required|string',
          'body_parameters' => 'required|array',
          'document_link' => 'nullable|url',
          'document_filename' => 'nullable|string',
      ]);

      $components = [];

      // Optional document (header)
      if ($validated['document_link']) {
          $components[] = [
              "type" => "header",
              "parameters" => [
                  [
                      "type" => "document",
                      "document" => [
                          "link" => $validated['document_link'],
                          "filename" => $validated['document_filename'] ?? 'Document.pdf'
                      ]
                  ]
              ]
          ];
      }

      // Body text parameters
      $bodyParams = [];
      foreach ($validated['body_parameters'] as $text) {
          $bodyParams[] = ["type" => "text", "text" => $text];
      }

      $components[] = [
          "type" => "body",
          "parameters" => $bodyParams
      ];

      $response = WhatsAppService::sendTemplateMessage([
          'phone' => $validated['phone'],
          'template_name' => $validated['template_name'],
          'template_language' => 'en',
          'components' => $components,
      ]);

      return response()->json($response);
  }
  </pre>


Endpoint :
<pre> POST /ajax/sendWhatsapp
</pre>
Sample JSON Payload (Body) : 
<pre>
{
  "phone": "919995060510",
  "template_name": "cellsquare_sale_msg_1",
  "body_parameters": [
    "Akarsh",
    "Digital Spot, Kaniyapuram",
    "INV-123123",
    "23-01-2023",
    "101.00",
    "Digital Spot, Kaniyapuram",
    "9999999999"
  ],
  "document_link": "https://www.antennahouse.com/hubfs/xsl-fo-sample/pdf/basic-link-1.pdf",
  "document_filename": "Invoice.pdf"
}
</pre>
