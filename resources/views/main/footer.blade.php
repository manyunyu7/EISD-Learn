<style>
    /* Penyesuaian untuk tampilan lebih kecil */
    @media screen and (max-width: 767px) {
        .row {
            flex-direction: column; /* Mengubah tata letak kolom menjadi satu di bawah yang lain */
            text-align: center; /* Pusatkan konten dalam kolom */
        }

        .col-4 {
            width: 100%; /* Gunakan lebar penuh pada tampilan layar yang lebih kecil */
            margin-bottom: 20px; /* Tambahkan margin antara kolom */
        }

        .footerSocmed {
            margin-left: 0; /* Atur margin kembali ke nilai default */
        }

        .col-4.footerSocmed {
            margin-right: 0; /* Atur margin kembali ke nilai default */
            margin-top: 0; /* Atur margin kembali ke nilai default */
        }
        .col-4{
            margin-left: 110px;
            column-width: 50%;
        }

        .footerLogo{
            max-width: 1120%; 
            max-height: auto;
            margin-left: 10px;
            align-items: center;
        }
        .footerAddress{
            margin-left: 30px;
            color: #8C94A3;
            padding: 1%;
            text-align: center;
        }
        .footerAddress b{
            font-size: 12px;
        }
        .footerCentral b{
            font-size: 12px;
            margin-left: 10px;
            color: #8C94A3;
            padding: 1%;
        }
        
        .social-icon {
            display: flex;
            margin-left: 80px;
            padding-right: 10px;
        }

        .social-icon a {
            align-items: center;
            background: hsla(#363B47);
            border: 1px solid hsla(0, 0%, 100%, 0.5);
            display: inline-flex;
            height: 28px;
            width: 28px;
            justify-content: center;
            line-height: 1;
            transition: background 0.3s, border-color 0.3s;
            margin: 0 10px;  /* Adjust as needed */
        }

        /* Efek hover */
        .social-icon a:hover {
            background: hsla(0, 100%, 57%, 0.913);
            border-color: hsla(0, 0%, 100%, 0.7);
        }
    }

    /* Penyesuaian untuk tampilan sedang */
    @media screen and (min-width: 820px) and (max-width: 1180px) and (orientation: portrait) {
        .footerLogo{
            max-width: 80%; 
            max-height: auto;
            margin-left: -30px;
            padding-right: 45%;
            align-items: center;
        }
        .footerAddress{
            margin-left: -30px;
            color: #8C94A3;
            padding-right: 45%;
        }
        .footerAddress b{
            font-size: 12px;
        }
        .footerCentral{
            color: #8C94A3;
            margin-right: 750px;
            margin-top: -120px;
            text-align: center;
        }
        .footerCentral b{
            font-size: 12px;
            margin-left: 220px;
            padding: 3px;
        }
        .footerSocmed{
            margin-left: 520px;
            padding-right: 110px;
            
        }
        .col-4.footerSocmed {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 850px;
            margin-top: -200px;
        }

        .social-icon {
            display: flex;
            margin-left: 500px;
            padding-right: 110px;
        }

        .social-icon a {
            align-items: center;
            background: hsla(#363B47);
            border: 1px solid hsla(0, 0%, 100%, 0.5);
            display: inline-flex;
            height: 40px;
            justify-content: center;
            line-height: 1;
            width: 40px;
            transition: background 0.3s, border-color 0.3s;
            margin: 0 10px;  /* Adjust as needed */
        }

        /* Efek hover */
        .social-icon a:hover {
            background: hsla(0, 100%, 57%, 0.913);
            border-color: hsla(0, 0%, 100%, 0.7);
        }
    }
    /* Penyesuaian untuk tampilan sedang */
    @media screen and (min-width: 820px) and (max-width: 1180px) and (orientation: landscape) {
        .footerLogo,
        .footerAddress,
        .footerCentral,
        .col-4.footerSocmed,
        .social-icon {
            margin: 0; /* Reset margin */
            padding: 0; /* Reset padding */
        }

        .footerLogo {
            max-width: 180%;
            max-height: auto;
            margin-left: -20px;
            padding-right: 45%;
            align-items: center;
        }

        .footerAddress {
            margin-left: -30px;
            color: #8C94A3;
            padding-right: 35%;
            b {
                font-size: 12px;
            }
        }

        .footerCentral {
            color: #8C94A3;
            text-align: center;
            margin-left: -350px;
            margin-top: 65px;
            b {
                font-size: 12px;
            }
        }

        .footerSocmed {
            /* Adjust margin and positioning as needed */
            margin-left: 0px;
            margin-bottom: 110px;
        }

        .col-4.footerSocmed {
            display: flex;
            justify-content: center;
            align-items: center;
            /* Adjust margin and positioning as needed */
            margin-right: -100px;
            margin-top: 0;
        }

        .social-icon {
            display: flex;
            /* Adjust margin and positioning as needed */
            margin-left: 850px;
            margin-top: -210px;
            padding-right: 10px;
        }

        .social-icon a {
            align-items: center;
            background: hsla(#363B47);
            border: 1px solid hsla(0, 0%, 100%, 0.5);
            display: inline-flex;
            height: 40px;
            justify-content: center;
            line-height: 1;
            width: 40px;
            transition: background 0.3s, border-color 0.3s;
            margin: 0 10px;  /* Adjust as needed */

            /* Efek hover */
            &:hover {
                background: hsla(0, 100%, 57%, 0.913);
                border-color: hsla(0, 0%, 100%, 0.7);
            }
        }
    }


    /* Penyesuaian untuk tampilan besar */
    @media screen and (min-width: 1440px) {
        .wrapper {
            display: grid;
            grid-template-columns : (auto-fit,
                                    minmax(min(11.25rem, 100%), 1 fr));
            grid-gap: 1 rem;
        }
        .footerLogo{
            max-width: 500%; 
            max-height: auto;
            margin-left: -30px;
            padding-right: 45%;
        }
        .footerAddress{
            margin-left: -30px;
            color: #8C94A3;
            padding-right: 45%;
        }
        .footerCentral{
            color: #8C94A3;
            margin-right: 750px;
            text-align: center;
        }
        .footerSocmed{
            margin-left: -1350px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 850px;
            margin-top: -150px;
            
        }

        .social-icon {
            display: flex;
            margin-left: 2100px;
            margin-top: -180px;
        }

        .social-icon a {
            align-items: center;
            background: hsla(#363B47);
            border: 1px solid hsla(0, 0%, 100%, 0.5);
            display: inline-flex;
            height: 55px;
            width: 55px;
            justify-content: center;
            line-height: 1;
            transition: background 0.3s, border-color 0.3s;
            margin: 0 10px;  /* Adjust as needed */
        }

        /* Efek hover */
        .social-icon a:hover {
            background: hsla(0, 100%, 57%, 0.913);
            border-color: hsla(0, 0%, 100%, 0.7);
        }
    }
</style>

<footer class=" footer" style="background-color: #1D2026">
    <div class="l-footer">
        <div class="row">
            <img src="{{URL::to('/')}}/logoPutih.png" alt="navbar brand" class="centered-image footerLogo">
        </div>
        <div class="footerAddress" >
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
        <div class="social-icon">
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
</footer>