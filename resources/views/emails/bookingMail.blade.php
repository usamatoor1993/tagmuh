{{-- <!DOCTYPE html>
<html>

<head>
    <title>Tagmuh.com</title>
</head>

<body>
    <h1>{{ $data['name'] }}</h1>
    <p>{{ $data['contact'] }}</p>

    <p>{{ $data['email'] }}</p>
    <p>{{ $data['adults'] }}</p>
    <p>{{ $data['childern'] }}</p>
    <p>{{ $data['description'] }}</p>
    <p>{{ $data['date'] }}</p>
    <p>{{ $data['time'] }}</p>
    <p>{{ $data['platform'] }}</p>
    <p>{{ $data['link'] }}</p>

    <p>Thank you</p>
</body>

</html> --}}


<!-- Free to use, HTML email template designed & built by FullSphere. Learn more about us at www.fullsphere.co.uk -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <!--[if gte mso 9]>
      <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG />
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
      </xml>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->

    <!-- Your title goes here -->
    <title>Tagmuh</title>
    <!-- End title -->
    <style>
      @media only screen and (max-width: 630px) {
        .div-box {
          display: flex;
          flex-direction: column !important;
        }
        .detail-box {
          min-width: 100% !important;
          padding-left: 0px !important;
          padding-right: 0px !important;
        }
        .detail-box div {
          margin-left: 10px !important;
          margin-right: 10px !important;
        }
      }
    </style>
  </head>

  <!-- You can change background colour here -->
  <body
    style="
      text-align: center;
      margin: 0;
      padding-top: 10px;
      padding-bottom: 20px;
      padding-left: 10px;
      padding-right: 10px;
      -webkit-text-size-adjust: 100%;
      background-color: #ffd153;
      color: #000000;
    "
    align="center"
  >
    <!-- Fallback force center content -->
    <div style="text-align: center">
      <!-- Start container for logo -->
      <table
        align="center"
        style="
          text-align: center;
          vertical-align: top;
          width: 100%;
          max-width: 100%;
          background-color: #ffff;
          border-top-left-radius: 20px;
          border-top-right-radius: 20px;
        "
        width="100%"
      >
        <tbody>
          <tr>
            <td
              style="
                width: 596px;
                vertical-align: top;
                padding-left: 0;
                padding-right: 0;
                padding-top: 15px;
                padding-bottom: 0px;
              "
              width="100%"
            >
              <!-- Your logo is here -->
              <img
                style="height: auto; width: 220px; text-align: center"
                alt="Logo"
                src="{{ asset('images/logo.png') }}"
                align="center"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <!-- End container for logo -->
      <!-- Start single column section -->
      <table
        align="center"
        style="
          text-align: center;
          vertical-align: top;
          width: 100%;
          max-width: 100%;
          background-color: #ffff;
          border-bottom-left-radius: 20px;
          border-bottom-right-radius: 20px;
          height: 100vh !important;
        "
        width="100%"
      >
        <tbody>
          <tr>
            <td
              style="width: 596px; vertical-align: top; padding-top: 10px"
              width="100%"
            >
              <div style="display: flex !important" class="div-box">
                <div
                  class="detail-box"
                  style="
                    width: 50% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Name:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                        word-break: break-all;
                      "
                      >{{ $data['name'] ?? ''}}</span
                    >
                  </div>
                </div>
                <div
                  class="detail-box"
                  style="
                    width: 50% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Phone Number:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                        word-break: break-all;
                      "
                      >{{ $data['contact'] ?? ''}}</span
                    >
                  </div>
                </div>
              </div>
              <div style="display: flex !important" class="div-box">
                <div
                  class="detail-box"
                  style="
                    width: 50% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Email Address:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                        word-break: break-all;
                      "
                      >{{ $data['email'] ?? ''}}</span
                    >
                  </div>
                </div>
                <div
                  class="detail-box"
                  style="
                    width: 50% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Adult:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                      "
                      >{{ $data['adults'] ?? ''}}</span
                    >
                  </div>
                </div>
              </div>
              <div style="display: flex !important" class="div-box">
                <div
                  class="detail-box"
                  style="
                    width: 50% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Time:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                      "
                      >{{ $data['time'] ?? ''}}</span
                    >
                  </div>
                </div>
                <div
                  class="detail-box"
                  style="
                    width: 50% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Date:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                      "
                      >{{ $data['date'] ?? ''}}</span
                    >
                  </div>
                </div>
              </div>
              <div style="display: flex !important">
                <div
                  class="detail-box"
                  style="
                    width: 100% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Childern:</span
                    >
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                        word-break: break-all;
                      "
                      >{{ $data['childern'] ?? ''}}</span
                    >
                  </div>
                </div>
              </div>
              <div style="display: flex !important">
                <div
                  class="detail-box"
                  style="
                    width: 100% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Description:</span
                    >
                    <br />
                    <span
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        word-break: break-all;
                      "
                      >{{ $data['description'] ?? ''}}
                      
                      </span
                    >
                  </div>
                </div>
              </div>
              <!-- <div style="display: flex !important">
                <div
                  class="detail-box"
                  style="
                    width: 100% !important;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 20px;
                  "
                >
                  <div
                    style="
                      background-color: #fafafa;
                      text-align: start;
                      padding-top: 17px;
                      padding-bottom: 17px;
                      padding-left: 12px;
                      border-radius: 10px;
                    "
                  >
                    <span
                      style="font-size: 16px; font-weight: 400; color: #8c959a"
                      >Google Meet Link:</span
                    >
                    <a
                      href="https://mail.google.com/mail/u/0/#inbox/FMfcgzQZSjbvRR"
                      style="
                        font-size: 18px;
                        font-weight: 500;
                        color: black;
                        padding-left: 10px;
                        word-break: break-all;
                      "
                      >https://mail.google.com/mail/u/0/#inbox/FMfcgzQZSjbvRR</a
                    >
                  </div>
                </div>
              </div> -->
              <p
                style="
                  font-size: 24px;
                  font-weight: 700;
                  color: #2070f7;
                  margin-top: 30px;
                "
              >
                Thank You!
              </p>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- End single column section -->
    </div>
  </body>
</html>
