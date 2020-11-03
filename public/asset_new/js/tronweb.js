//show eIDR balance
    async function showTronBalance() {
      if (window.tronWeb && window.tronWeb.defaultAddress.base58) {
        const userAddress = tronWeb.defaultAddress.base58;
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
          $("#saldo-eidr").html(
            `<small>Saldo eIDR anda:</small> <h4 id="eidr-balance" class="text-success">` +
              eIDRarray.value / 100 +
              `<h4 class="text-success"> eIDR</h4>`
          );

          $("#userTron").val(userAddress);
          $("#showAddress").html(`<small>Active Wallet: ` + userAddress + `</small> `);
          $('#isTronWeb').val(1);

        } else {
          $("#saldo-eidr").html(`<h5>Alamat TRON ini tidak memiliki eIDR</h5>`);
        }
      }
    }

    setTimeout(() => showTronBalance(), 2000);

//Pay Royalty by TronWeb service
$("#eidr-pay-button").click(async function () {
  const userAddress = tronWeb.defaultAddress.base58;
  const toAddress = "TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ";
  let sendAmount = $("#royalti").val() * 100;
  var tronweb = window.tronWeb;
  try {
    var tx = await tronweb.transactionBuilder.sendAsset(
      toAddress,
      sendAmount,
      "1002652",
      userAddress
    );
    var signedTx = await tronweb.trx.sign(tx);
    var broastTx = await tronweb.trx.sendRawTransaction(signedTx);
    if (broastTx.result) {
      alert("Transaksi Berhasil, Silakan Klik Konfirmasi!");
      $('#hash').val(broastTx.txid);
      $('#submit').removeAttr("disabled");

    } else {
      alert("Transaksi Gagal! Cek kembali koneksi anda lalu ulangi");
    }
  } catch (e) {
    if (e.includes("assetBalance is not sufficient")) {
      alert("Saldo eIDR tidak mencukupi");
    } else if (e.includes("assetBalance must be greater than")) {
      alert("Alamat TRON ini tidak memiliki eIDR");
    } else {
      console.log(e);
    }
  }
});
