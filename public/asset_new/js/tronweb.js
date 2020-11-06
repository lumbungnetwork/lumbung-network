
let txType = $('#txType').val();
var toAddress, userAddress;
let sendAmount = 0;



$(document).ready(function(){
    setTimeout(function() {
        showTronBalance()
    }, 1000)
});

function shortId(a,b){return a.substr(0,b)+"..."+a.substr(a.length-b,a.length)}

//show eIDR balance
    async function showTronBalance() {
      if (window.tronWeb && window.tronWeb.defaultAddress.base58) {
        userAddress = tronWeb.defaultAddress.base58;
        let tokenBalancesArray;
        let balanceCheck = await tronWeb.trx
          .getAccount(userAddress)
          .then((result) => (tokenBalancesArray = result.assetV2));
        balanceCheck;
        let eIDRexist = await tokenBalancesArray.some(function (tokenID) {
          return tokenID.key == "1002652";
        });
        if (eIDRexist) {
          let eIDRarray = await tokenBalancesArray.find(function (tokenID) {
            return tokenID.key == "1002652";
          });
          let eIDRbalance = eIDRarray.value / 100;

          $("#saldo-eidr").html(
            `
            <input type="hidden" value="${eIDRbalance}" name="eidr-balance" id="eidr-balance">
            <small>Saldo eIDR anda:</small> <h5 class="text-success">${eIDRbalance.toLocaleString("id-ID")} eIDR</h5>`
          );

          $("#userTron").val(userAddress);
          $("#showAddress").html(`<p>Active Wallet: <mark>${shortId(userAddress,5)}</mark></p> `);
          $('#isTronWeb').val(1);

        } else {
          $("#saldo-eidr").html(`<h5>Alamat TRON ini tidak memiliki eIDR</h5>`);
        }
      }
    }



//Pay using TronWeb service
$("#eidr-pay-button").click(async function () {
    if (txType == 1) {
        toAddress = "TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ";
        sendAmount = $("#royalti").val().trim() * 100;
    } else if (txType == 2) {
        toAddress = "TDtvo2jCoRftmRgzjkwMxekh8jqWLdDHNB";
    } else {
        toAddress = "TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge";
        sendAmount = $('#deposit').val().trim() * 100;
    }

    var tronweb = window.tronWeb;
    try {
        var tx = await tronweb.transactionBuilder.sendAsset(
        toAddress,
        sendAmount,
        "1002652",
        userAddress,
        );
        var signedTx = await tronweb.trx.sign(tx);
        var broastTx = await tronweb.trx.sendRawTransaction(signedTx);
        if (broastTx.result) {
            alert("Transaksi Berhasil, Silakan Klik Konfirmasi!");
            $('#hash').val(broastTx.txid);
            $('#submit').removeAttr("disabled");

        } else {
            alert("Transaksi Gagal! Cek koneksi anda, Restart Aplikasi ini, lalu ulangi");
            $('#submit').remove();
            $('.modal-footer').append('<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>');
        }
    } catch (e) {
        if (e.includes("assetBalance is not sufficient")) {
            alert("Saldo eIDR tidak mencukupi");
        } else if (e.includes("assetBalance must be greater than")) {
            alert("Alamat TRON ini tidak memiliki eIDR");
        } else if (e.includes("declined by user")) {
            alert("Anda membatalkan Transaksi");
            $('#confirmSubmit').modal("hide");
        } else {
            console.log(e);
        }
    }
});


