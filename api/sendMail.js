const sgMail = require("@sendgrid/mail");

export default function handler(request, response) {
  sgMail.setApiKey(process.env.SENDGRID_API_KEY);

  const msg = {
    to: "hello@flystraight.media",
    from: "hello@flystraight.media",
    subject: `FLY STRAIGHT MEDIA Contact Form`,
    text: `${request.body.name}\n${request.body.email}\n\n${request.body.message}`,
  };

  sgMail
    .send(msg)
    .then(() => {
      response.status(204).send();
    })
    .catch((error) => {
      console.error(`Failed to send mail. Sendgrid error response: ${error}`);
      response.status(500).send();
    });
}
