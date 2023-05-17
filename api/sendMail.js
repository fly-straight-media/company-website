const sgMail = require("@sendgrid/mail");

export default function handler(request, response) {
  sgMail.setApiKey(process.env.SENDGRID_API_KEY);

  const msg = {
    to: "hello@flystraight.media",
    from: "hello@flystraight.media",
    subject: `Contact Form - ${request.body.name}`,
    text: `${request.body.email}\n${request.body.message}`,
  };

  sgMail
    .send(msg)
    .then((response) => {
      response.status(204).send();
    })
    .catch((error) => {
      console.error(`Failed to send mail. Sendgrid error response: ${error}`);
      response.status(500).send();
    });
}
