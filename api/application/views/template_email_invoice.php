<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Email Notifikasi Forget Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style type="text/css">
    a{
        text-decoration: none!important;
        color: #04b1f5;
    }
    td{
        padding: 10px 0; color:   #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 26px;
    }
    tr.small td{
        padding: 5px 0px!important;
    }
</style>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
        <tr>
            <td style="padding: 10px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="650" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr>
                        <td align="center" bgcolor="#2d2d2d" style="padding: 15px 0 10px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
                            <img src="<?php echo $base_url.'assets/images/logo.png'; ?>" alt="Logo Koperasi Artakita" width="200" height="200" style="display: block;" />
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
                                        <b><?php echo $subject; ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Hai member Artakita,
                                        <br>
                                        Kami telah menerima konfirmasi pembayaran kamu untuk request akun owner di Aplikasi <a href="<?php echo $base_url; ?>">Koperasi Artakita</a>. Berikut ini datanya :
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr class="small">
                                                <td>Invoice :</td>
                                                <td><?php echo isset($pembayaran['no_invoice']) ? $pembayaran['no_invoice'] : ""; ?></td>
                                            </tr>
                                            <tr class="small">
                                                <td>Tgl Request :</td>
                                                <td><?php echo isset($pembayaran['tgl_request']) ? $pembayaran['tgl_request'] : ""; ?></td>
                                            </tr>                 
                                            <tr class="small">
                                            	<td>Tgl Pembayaran : </td>
                                                <td><?php echo isset($pembayaran['tgl_pembayaran']) ? $pembayaran['tgl_pembayaran'] : ""; ?></td>
                                            </tr>                          
                                            <tr class="small">
                                                <td>Nama Pembayar : </td>
                                                <td><?php echo isset($pembayaran['nama_pembayar']) ? $pembayaran['nama_pembayar'] : ""; ?></td>
                                            </tr>    
                                             <tr class="small">
                                                <td>No. HP Pembayar : </td>
                                                <td><?php echo isset($pembayaran['no_hp_pembayar']) ? $pembayaran['no_hp_pembayar'] : ""; ?></td>
                                            </tr> 
                                             <tr class="small">
                                                <td>Email Pembayar : </td>
                                                <td><?php echo isset($pembayaran['email_pembayar']) ? $pembayaran['email_pembayar'] : ""; ?></td>
                                            </tr>    
                                            <tr class="small">
                                                <td>Jumlah Pembayar : </td>
                                                <td><?php echo isset($pembayaran['jumlah_pembayaran']) ? "Rp. ".number_format($pembayaran['jumlah_pembayaran'],0,'.','.') : ""; ?></td>
                                            </tr>                          
                                            <tr class="small">
                                                <td></td>                                                
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Konfirmasi pembayaran kamu akan segera kami proses. Terima kasih</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#9dc105" style="padding: 0px 20px ;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
                                        &reg; Koperasi Artakita <?php echo date('Y'); ?><br/>
                                        
                                    </td>
                                    
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>