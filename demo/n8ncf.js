var el = document.querySelector("#postData");
if (el) {
  el.addEventListener("submit", postData);
}
function postData(event) {
  event.preventDefault();
  let message = document.querySelector("#message").value;
  let cf = document.getElementsByName("cf-turnstile-response")[0].value;
  async function cfverify() {
    let response = await fetch(
      "http://localhost:6001/cf.php?cfresponse=" + cf
    );
    const json = await response.json();
    if (json.success === true) {
      if (message == 0) {
        if (document.getElementById("notice") != null) {
          document.getElementById("notice").innerHTML =
            "<p>Empty Auth Code</p>";
        }
      } else {
        const user_data = message;
        fetch("http://localhost:6001/ping.php?message=" + user_data, {
          method: "GET",
        })
          .then(async (response) => {
            const verify_data = await response.json();
            if (verify_data.message === "true") {
              if (document.getElementById("notice") != null) {
                el.reset(),
                  (document.getElementById("notice").innerHTML =
                    '<p style="text-align: center;">New Data Request pinged</p>');
              }
            } else {
              if (document.getElementById("notice") != null) {
                document.getElementById("notice").innerHTML =
                  '<p style="text-align: center;">Failed to ping - Wrong Auth Code</p>';
              }
            }
          })
          .catch((error) => {
            if (document.getElementById("notice") != null) {
              document.getElementById("notice").innerHTML =
                '<p style="text-align: center;">API Connection Error</p>';
            }
          });
      }
    } else {
      if (document.getElementById("notice") != null) {
        document.getElementById("notice").innerHTML =
          '<p style="text-align: center;">Verification Error - Refresh the page to Reverify the Captcha</p>';
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      }
    }
  }
  cfverify();
}