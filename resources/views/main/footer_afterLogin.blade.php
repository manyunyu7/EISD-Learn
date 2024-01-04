
{{-- <style>
    .footerLogo {
        max-width: 100%;
        max-height: auto;
        margin-left: 20px;
        margin-top: -30px;
        padding-right: 50%;
        align-items: center;
    }
    .footerSocmed {
        /* Adjust margin and positioning as needed */
        margin-left: 0px;
        margin-bottom: 110px;
    }
    .social-icon a {
        align-items: center;
        background: hsla(#363B47);
        border: 1px solid hsla(0, 0%, 100%, 0.5);
        display: inline-flex;
        height: 50px;
        width: 50px;
        justify-content: center;
        line-height: 1;
        transition: background 0.3s, border-color 0.3s;
        margin: 0 10px;  /* Adjust as needed */

        /* Efek hover */
        &:hover {
            background: hsla(0, 100%, 57%, 0.913);
            border-color: hsla(0, 0%, 100%, 0.7);
        }
    }
    .social-icon {
        display: flex;
        /* Adjust margin and positioning as needed */
        margin-left: 850px;
        margin-top: 50px;
        margin-bottom: 80px;
        padding-right: 10px;
    }
    .footerCentral {
            color: #8C94A3;
            text-align: center;
            margin-left: 550px;
            margin-top: 65px;
            b {
                font-size: 12px;
            }
            
        }
</style> --}}
<style>
    .footer {
        background-color: #1D2026;
        color: white;
        padding: 20px;
        margin-top: 20px;
    }

    .footerLogo {
        max-width: 20%; /* Adjust the maximum width of the image */
        max-height: 20vh; /* Adjust the maximum height of the image */
    }

    .footerAddress {
        color: white;
    }

    .footer-icon {
        display: flex;
        gap: 10px;
    }

    .footer-icon img {
        width: 30px; /* Adjust the size of social icons */
        height: auto;
    }
</style>
<footer class="footer container-fluid">
    <div class="container-fluid">
        <div class="l-footer">
            <div class="row">
                <img src="{{URL::to('/')}}/logoPutih.png" alt="navbar brand" class="centered-image footerLogo">
            </div>
            <div class="footerAddress">
                <br>
                <b>Commercial Area 5th, Green Central City, Jl.Gajah Mada</b><br>
                <b>RT.3/RW.5, Glodok, Kec. Taman Sari</b><br>
                <b>Jakarta, Daerah Khusus Ibukota Jakarta</b><br>
                <b>11120</b><br>
            </div>
        </div>
        <ul class="r-footer">
            <div class="row footerCentral">
                <br><br><br><br><br>
                <b style="color: white">You're not Login (Login Here)</b>
                <b>Copyright ©2024 PT. Modernland Realty Tbk.</b>
                <b>All Right Reserved</b>
            </div>
        </ul>
        <div class="b-footer">
            <div class="footer-icon">
                <a href="https://facebook.com/modernlandrealty/">
                    <img src="{{URL::to('/')}}/Facebook.svg" alt="">
                </a>
                <a href="https://www.instagram.com/modernlandrealty">
                    <img src="{{URL::to('/')}}/Instagram.svg" alt="">
                </a>
                <a href="https://www.linkedin.com/company/modernland/mycompany/">
                    <img src="{{URL::to('/')}}/Linkedin.svg" alt="">
                </a>
                <a href="https://www.youtube.com/channel/UCY55_aSJ51DrMuucN4M4kuw">
                    <img src="{{URL::to('/')}}/Youtube.svg" alt="">
                </a>
            </div>
        </div>
    </div>
</footer>