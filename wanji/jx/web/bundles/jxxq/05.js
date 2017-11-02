(function() {
	var a = function() {
		var E, N, y, aa, B, K, x, R, ac, P;
		var ad, S, af, ae, Y, G, U, X, M, ab, Q, I;
		var L = "http://" + location.host + "/assets/touch/img/gallery_img_default.png";
		function F() {
			ad = J.g("pop");
			E = J.g("navTab");
			slider = J.g("slider");
			ab = J.g("picDesc");
			P = J.g("curImage");
			Q = ab && ab.s("span").eq(0);
			S = ad.s(".pop_close") && ad.s(".pop_close").eq(0);
			aa = ad.s(".cur_index") && ad.s(".cur_index").eq(0);
			x = ad.s(".totle_num").length > 0 && ad.s(".totle_num").eq(0);
			ac = ad.s(".pop_up") && ad.s(".pop_up").eq(0);
			af = ad.s(".pop_cover") && ad.s(".pop_cover").eq(0);
		}
		function e() {
			var ag = location.pathname.split("/");
			var aj = (ag.length > 2) ? ag[2].toLowerCase() : null;
			y = [0];
			x.html(ad.s(".pop_cover") && ad.s(".pop_cover").eq(0).attr("data-count"));
			if (!af.attr("data-array")) {
				N = [af.attr("data-count") - 1, 1];
			} else {
				N = V(af.attr("data-array").split(","));
				if (N[N.length - 1] == "") {
					N.pop();
				}
			}
			for (var ai = 0,
			ah = 0; ai < N.length - 1; ai++) {
				ah = ah + parseInt(N[ai]);
				y.push(ah);
			}
			if (ab && Q) {
				var ak = slider.s(".gDesc");
				if (ak.length > 0) {
					Q.html(ak.eq(parseInt(aa.html()) - 1).html());
				}
			}
		}
		function O() {
			if (S) {
				S.on("click",
				function() {
					T.trackEvent("track_gallery_" + M + "_close");
					A();
				});
			}
			if (slider.down(0)) {
				slider.down(0).on("click",
				function(ah) {
					T.trackEvent("track_gallery_" + M + "_close");
					ah = window.event || ah;
					var ag = J.g(ah.target || ah.srcElement);
					A();
				});
			}
			if (I.swipe) {
				I.swipe.on("click",
				function(ag) {
					T.trackEvent("track_gallery_" + M + "_open");
					ag = window.event || ag;
					if (window.event) {
						ag.returnValue = false;
					} else {
						ag.preventDefault();
					}
					if (!Y) { (function() {
							B = new iScroll("navHead");
						}.require(["touch.iscroll"])); (function() {
							U();
						}.require(["touch.swipe"]));
						if (X) {
							z();
						}
					}
					A();
				});
			}
			if (P) {
				P.get().onload = function(ai) {
					var ah = parseInt(aa.html()) - 1;
					if (slider.s("img").length > ah) {
						var ag = slider.s("img").eq(ah);
					}
					if (ag) {
						D(ag, [this.height, this.width]);
						ag.attr("src", J.g(this).attr("src")).attr("h", this.height).attr("w", this.width);
					}
				};
			}
			window.addEventListener("resize", W, false);
		}
		function W() {
			clearTimeout(R);
			R = setTimeout(function() {
				var ag = parseInt(aa.html()) - 1;
				if (G) {
					J.s(".V").eq(0).setStyle({
						height: J.page.viewHeight() + "px",
						overflow: "hidden"
					});
					ac.setStyle({
						height: J.page.viewHeight() + "px"
					});
				} else {
					J.s(".V").eq(0).setStyle({
						height: "auto",
						overflow: "auto"
					});
					ac.setStyle({
						height: "auto"
					});
				}
				Z(ag);
				if (E.width() > J.page.viewWidth()) {
					X = true;
					z();
				} else {
					X = false;
				}
			},
			200);
		}
		function Z(ah) {
			var ag = J.g("slider").s("img").eq(ah),
			ai;
			if (ag) {
				ai = D(ag);
				if (ag.attr("src") == L) {
					P.attr("src", ai);
				} else {
					ai && ag.attr("src", ai);
				}
			}
		}
		function D(ah, aq) {
			var al, ai, an, ap, ao, aj, am, ag, ak;
			ap = ah.attr("data-src-swipe");
			ao = ap.split("/").pop().match(/\d+x\d+/g);
			if (aq) {
				al = aq[1];
				ai = aq[0];
			} else {
				al = ah.width();
				ai = ah.height();
			}
			am = J.page.viewHeight();
			ag = J.page.viewWidth();
			if (window.devicePixelRatio && window.devicePixelRatio > 1) {
				al = parseInt(al * (window.devicePixelRatio - 0.5));
				ai = parseInt(ai * (window.devicePixelRatio - 0.5));
			}
			if (al / ai >= ag / am) {
				an = al;
				al = ag;
				ai = parseInt(ag * ai / an);
				ak = {
					width: al + "px",
					height: "auto"
				};
			} else {
				an = ai;
				ai = am;
				al = parseInt(al * am / an);
				ak = {
					height: ai + "px",
					width: "auto"
				};
			}
			ap = ap.replace(ao, parseInt(al + 100) + "x" + parseInt(ai + 100));
			ah.setStyle(ak);
			return ap;
		}
		function A() {
			if (G) {
				ad.addClass("hidepop");
				J.s(".V").eq(0).setStyle({
					height: "auto",
					overflow: "auto"
				});
				ac.setStyle({
					height: "auto"
				});
				J.s(".H").eq(0).setStyle({
					"z-index": "10000000002"
				});
				G = false;
			} else {
				Z(parseInt(aa.html()) - 1);
				ad.removeClass("hidepop");
				J.s(".V").eq(0).setStyle({
					height: J.page.viewHeight() + "px",
					overflow: "hidden"
				});
				ac.setStyle({
					height: J.page.viewHeight() + "px"
				});
				J.s(".H").eq(0).setStyle({
					"z-index": "2"
				});
				G = true;
			}
		}
		function U() {
			K = Swipe(slider.get(), {
				continuous: false,
				callback: function(ah, aj) {
					aa.html(ah + 1);
					if (ab && Q) {
						var al = slider.s(".gDesc");
						if (al.length > 0) {
							Q.html(al.eq(parseInt(aa.html()) - 1).html());
						}
					}
					if (M == "sale") {
						T.trackEvent("track_gallery_" + M + "_move");
					}
					Z(ah);
					for (var ai = 0,
					ag = N.length,
					ak = 0; ai < ag; ai++) {
						if (ah >= y[ai] && ai != ag - 1) {
							continue;
						}
						ak = (ah < y[ai]) ? (ai - 1) : ai;
						if (ak == ae) {
							return;
						}
						E.s("li").eq(ae).down(0).removeClass("active");
						E.s("li").eq(ak).down(0).addClass("active");
						ae = ak;
						if (X) {
							z();
						}
						return;
					}
				}
			});
			if (!Y) {
				if (E) {
					E.on("click",
					function(aj) {
						aj = window.event || aj;
						var ai, ah, ag, ak;
						ai = J.g(aj.target || aj.srcElement);
						if (ai.get().tagName.toLowerCase() == "span") {
							ak = H(ai.html());
							T.trackEvent(ak);
							ah = parseInt(ai.up(0).attr("data-index"));
							ag = y[ah];
							if (ag != undefined) {
								K.slide(ag, 0);
								if (ae != ah) {
									E.s("li").eq(ae).down(0).removeClass("active");
									E.s("li").eq(ah).down(0).addClass("active");
									ae = ah;
								}
							}
						}
					});
					Y = true;
				}
			}
		}
		function H(ag) {
			var ah;
			switch (ag) {
			case "室内图":
				ah = "shinei";
				break;
			case "户型图":
				ah = "huxing";
				break;
			case "实景图":
				ah = "shijing";
				break;
			case "样板间图":
				ah = "ybj";
				break;
			case "规划图":
				ah = "guihua";
				break;
			case "位置图":
				ah = "weizhi";
				break;
			case "配套图":
				ah = "peitao";
				break;
			case "效果图":
				ah = "xiaoguo";
				break;
			case "房型图":
				ah = "fangxing";
				break;
			case "小区图":
				ah = "xiaoqu";
				break;
			default:
				ah = "other";
				break;
			}
			return "track_gallery_" + M + "_" + ah;
		}
		function C() {
			var ag = location.pathname.split("/");
			M = (ag.length > 2) && ag[2];
			if (M == "sp" || M == "xzl") {
				M = M + "_" + ag[3];
			}
		}
		function V(ah) {
			var aj = [];
			for (var ai = 0,
			ag = ah.length; ai < ag; ai++) {
				if (ah[ai] != 0) {
					aj.push(ah[ai]);
				}
			}
			return aj;
		}
		function z() {
			var ai = E.width() - J.page.viewWidth();
			if (ai > 0 && B) {
				var ah = J.g("navTab").s("li").eq(ae),
				aj;
				var ag = J.g("navTab").s("li").length;
				if (ae == 0) {
					B.scrollTo(1, 0, 500);
				} else {
					aj = ah.prev();
					if (J.g("navTab").s("li").eq(ag - 1).offset().x - aj.offset().x >= J.page.viewWidth()) {
						B.scrollTo( - aj.offset().x, 0, 500);
					} else {
						B.scrollTo( - ai, 0, 500);
					}
				}
			} else {
				E.setStyle({
					left: "auto",
					position: "relative"
				});
			}
		}
		return {
			init: function() {
				I = a.backData();
				if (J.g("pop") && I) {
					X = true;
					G = false;
					Y = false;
					ae = 0;
					C();
					F();
					e();
					O();
				}
			},
			backData: function() {
				return {
					swipe: J.g("swipe"),
					curIndex: g
				};
			},
			switchPopState: A
		};
	} ();
	var g = {
		Rent_View: function() {
			var e = J.g("Rent_View_C");
			T.setCookie("touchweb_viewed_rentids", e.attr("ctid") + "-" + e.attr("ppc"));
			n("touchweb_favorites_rentids", e.attr("ctid") + "-" + e.attr("ppc"), e.attr("ppc"));
			h();
			b();
		},
		Anjuke_Prop_View: function() {
			var e = J.g("Prop_View_C"),
			y = e.attr("fyid"),
			x = e.attr("fyisauction");
			T.setCookie("touchweb_viewed_propids", e.attr("ctid") + "-" + y);
			n("touchweb_favorites_propids", e.attr("ctid") + "-" + y, y);
			h();
			b();
		},
		TuanGou_View: function() {
			m("Track_TuanView_Sign", "3");
		},
		Fang_View: function() {
			var x = J.g("prop_f_pop");
			T.setCookie("touchweb_viewed_houseids", x.attr("ctid") + "-" + x.attr("ppc") + "-" + x.attr("timetag"));
			v();
			l();
			var e = J.g("lookpic");
			e && e.on("click",
			function() {
				e.addClass("cutout");
				e.up(0).addClass("cutout");
				J.s(".posnum").each(function(z, y) {
					y.removeClass("cutout");
					y.s("img").each(function(A, B) {
						B.attr("src", B.attr("click-src"));
					});
				});
			});
			n("touchweb_favorites_houseids", x.attr("ctid") + "-" + x.attr("ppc") + "-" + x.attr("timetag"), x.attr("ppc"));
			h();
		},
		Xinfang_Loupan_View: function() {
			var x = J.g("prop_f_pop"),
			e = J.g("chanBatch");
			T.setCookie("touchweb_viewed_loupanids", x.attr("ctid") + "-" + x.attr("ppc") + "-" + x.attr("timetag"));
			m("Track_LoupanView_Sign", "4");
			n("touchweb_favorites_loupanids", x.attr("ctid") + "-" + x.attr("ppc") + "-" + x.attr("timetag"), x.attr("ppc"), "loupan");
			h();
			b();
		},
		Purchase_Business_View: function() {
			var e = J.g("prop_f_pop");
			T.setCookie("touchweb_viewed_pbusinessids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"));
			n("touchweb_favorites_pbusinessids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"), e.attr("ppc"), "business");
			h();
			b();
		},
		Purchase_Office_View: function() {
			var e = J.g("prop_f_pop");
			T.setCookie("touchweb_viewed_pofficeids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"));
			n("touchweb_favorites_pofficeids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"), e.attr("ppc"), "office");
			h();
			b();
		},
		Rental_Business_View: function() {
			var e = J.g("prop_f_pop");
			T.setCookie("touchweb_viewed_rbusinessids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"));
			n("touchweb_favorites_rbusinessids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"), e.attr("ppc"), "business");
			h();
			b();
		},
		Rental_Office_View: function() {
			var e = J.g("prop_f_pop");
			T.setCookie("touchweb_viewed_rofficeids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"));
			n("touchweb_favorites_rofficeids", e.attr("ctid") + "-" + e.attr("ppc") + "-" + e.attr("timetag"), e.attr("ppc"), "office");
			h();
			b();
		},
		Fang_Visit_View: function() {
			var e = J.g("signBut");
			e.on("click",
			function(x) {
				x.stop();
				T.trackEvent("track_kft_sign");
				setTimeout(function() {
					location.href = e.attr("href");
				},
				100);
			});
		},
		Fang_Visit_Signup: function() {
			var x = J.g("name"),
			D = J.g("nameTip"),
			E = J.g("bmtel"),
			z = J.g("bmtelTip"),
			C = J.g("peopNum"),
			A = J.g("bmBut"),
			H = J.g("lessBtn"),
			y = J.g("moreBtn");
			x.on("blur", B);
			function B() {
				var I = x.val().replace(/(^\s*)|(\s*$)/g, "");
				if (I == "") {
					D.show();
					return false;
				} else {
					D.hide();
					return true;
				}
			}
			E.on("blur", G);
			function G() {
				var I = E.val().replace(/(^\s)|(\s*$)/g, "");
				if (I == "") {
					z.show();
					z.html("手机号不能为空");
					return false;
				} else {
					if (/^1(3|5|8|4)[0-9]{9}$/.test(I)) {
						z.hide();
						return true;
					} else {
						z.show();
						z.html("手机号格式不正确");
						return false;
					}
				}
			}
			C.on("keyup",
			function() {
				var I = C.val().substr(0, 1);
				if (I == "0") {
					C.val("");
					return false;
				}
				e();
			});
			function e() {
				var I = C.val();
				if (I != 0) {
					H.addClass("less02");
				} else {
					H.removeClass("less02");
				}
			}
			y.on("click",
			function() {
				var I = C.val();
				if (I < 99) {
					I++;
					C.val(I);
				}
				e();
			});
			H.on("click",
			function() {
				var I = C.val();
				if (I > 0) {
					I--;
					C.val(I);
				}
				e();
			});
			A.on("click",
			function() {
				if (C.val() != "") {
					var I = "/kanfangtuan/" + A.attr("tag") + "/signup/check?phone=" + E.val() + "&num=" + C.val() + "&name=" + x.val();
				} else {
					var I = "/kanfangtuan/" + A.attr("tag") + "/signup/check?phone=" + E.val() + "&num=1&name=" + x.val();
				}
				if (B() && G()) {
					J.get({
						url: I,
						onSuccess: function(K) {
							if (K == 0) {
								location.href = location.pathname + "result/?phone=" + E.val();
								x.val("");
								E.val("");
								C.val("");
							} else {
								if (K == 5) {
									F("已经报名");
								} else {
									F("报名失败");
								}
							}
						},
						onFailure: function(K) {
							F("报名失败");
						}
					});
				}
			});
			function F(K) {
				var K;
				if (!J.g("bm_pop_errorbox")) {
					J.g("pageInit").append(J.create("div", {
						id: "bm_pop_errorbox"
					}));
					var I = "<div class='errorbox'><em></em><span>" + K + "</span></div> ";
					J.g("bm_pop_errorbox").html(I);
				}
				J.g("bm_pop_errorbox").show();
				setTimeout(function() {
					J.g("bm_pop_errorbox").setStyle({
						display: "none"
					});
				},
				2000);
			}
		}
	};
	var t = "";
	function s() {
		var C = J.g("pageInit").attr("pageName"),
		A = /MicroMessenger/i;
		if (!J.g("swipe_default")) { (function() {
				p(J.g("body").attr("data-page"));
			}.require(["touch.swipe"]));
		}
		if (J.g("baiduMap")) {
			f();
		} else {
			if (J.g("baiduMap_kft")) {
				k();
			}
		}
		lazyLoad = new T.lazyLoad({
			offsetY: 300
		});
		if (A.test(navigator.userAgent)) {
			t = "#mp.weixin.qq.com";
		}
		if (rentRec = J.g("recomm")) {
			var e = J.g("recomm"),
			z = e.attr("type"),
			y = e.attr("tag"),
			x = e.attr("data-src"),
			B = e.attr("data-nopic");
			J.get({
				url: "/" + J.site.info.cityAlias + "/" + z + "/recommend/" + y,
				data: {
					TP: "RECOMM"
				},
				type: "json",
				timeout: 15000,
				onSuccess: function(I) {
					if (I.recomofview == "") {
						rentRec.setStyle({
							display: "none"
						});
						return;
					}
					if (J.g("recomm") && J.g("recomm").getStyle("display") == "none") {
						J.g("recomm").setStyle({
							display: "block"
						});
					}
					if (z === "sale") {
						var U = "",
						R = I.recomofview,
						V = I.total,
						G, Q = I.recomofnearby,
						K = "";
						for (O = 0; O < R.length; O++) {
							if (R[O].IMAGESRC.indexOf("nopic_150x113.gif") < 0 && R[O].IMAGESRC != "") {
								G = R[O].IMAGESRC;
							} else {
								G = B;
							}
							U += '<a data-trace="{Exp_Recommend:' + R[O].PROID + '}" class="lv prop_rec_list c53"  href="/' + J.site.info.cityAlias + "/sale/" + R[O].PROID + "/?from=prop_recomm&isauction=" + R[O].isauction + t + '" data-isa="' + R[O].isauction + '" data-city="' + J.site.info.cityAlias + '" data-id="' + R[O].PROID + '"><img src=' + x + " data-src=" + G + '><span class="lv1"><span>' + R[O].TITLE + "</span><span>" + R[O].AREANAME + "&nbsp;&nbsp;" + R[O].COMMNAME + "</span><span>" + R[O].ROOMNUM + "室" + R[O].HALLNUM + "厅&nbsp;&nbsp;" + Math.round(R[O].AREANUM) + "平米<i><b>" + Math.round(R[O].PROPRICE) + "</b>万</i></span></span></a>";
						}
						for (O = 0; O < Q.length; O++) {
							if (O < 3) {
								if (Q[O].IMAGESRC.indexOf("nopic_150x113.gif") < 0 && Q[O].IMAGESRC != "") {
									G = Q[O].IMAGESRC;
								} else {
									G = B;
								}
								K += '<a data-trace="{Exp_Recommend:' + Q[O].PROID + '}" class="lv prop_rec_list c53"  href="/' + J.site.info.cityAlias + "/sale/" + Q[O].PROID + "/?from=prop_recomm_nearby&isauction=" + Q[O].isauction + '" data-isa="' + Q[O].isauction + '" data-city="' + J.site.info.cityAlias + '" data-id="' + Q[O].PROID + '"><img src=' + x + " data-src=" + G + '><span class="lv1"><span>' + Q[O].TITLE + "</span><span>" + Q[O].AREANAME + "&nbsp;&nbsp;" + Q[O].COMMNAME + "</span><span>" + Q[O].ROOMNUM + "室" + Q[O].HALLNUM + "厅&nbsp;&nbsp;" + parseInt(Q[O].AREANUM) + "平米<i><b>" + parseInt(Q[O].PROPRICE) + "</b>万</i></span></span></a>";
							}
						}
					} else {
						if (z == "rent") {
							var U = "",
							R = I.recomofview,
							V = I.total,
							G, Q = I.recomofnear,
							K = "";
							for (O = 0; O < R.length; O++) {
								if (R[O].photo.indexOf("nopic_150x113.gif") < 0 && R[O].photo != "") {
									G = R[O].photo;
								} else {
									G = B;
								}
								U += '<a data-trace="{Exp_Recommend:' + R[O].id + '}" class="lv prop_rec_list c53"  href="/' + J.site.info.cityAlias + "/rent/" + R[O].id + "-" + R[O].type + "/?from=rent_recomm&isauction=" + R[O].PROTYPE + t + '" data-isa="' + R[O].PROTYPE + '" data-city="' + J.site.info.cityAlias + '" data-id="' + R[O].id + "-" + R[O].type + '"><img src=' + x + " data-src=" + G + '><span class="lv1"><span>' + R[O].title + "</span><span>" + R[O].block.name + "&nbsp;&nbsp;" + R[O].community.name + "</span><span>" + R[O].roomnum + "室" + R[O].hallnum + "厅&nbsp;&nbsp;" + R[O].renttype + "<i><b>" + Math.round(R[O].price) + "</b>元/月</i></span></span></a>";
							}
							for (O = 0; O < 3; O++) {
								G = i(Q[O].photo, B);
								K += '<a data-trace="{Exp_Recommend:' + Q[O].id + '}" class="lv prop_rec_list c53" href="/' + J.site.info.cityAlias + "/rent/" + Q[O].id + "-" + Q[O].type + "/?from=rent_recomm_nearby&isauction=" + Q[O].PROTYPE + t + '" data-isa="' + Q[O].PROTYPE + '" data-city="' + J.site.info.cityAlias + '" data-id="' + Q[O].id + "-" + Q[O].type + '"><img src=' + x + " data-src=" + G + '><span class="lv1"><span>' + Q[O].title + "</span><span>" + Q[O].block.name + "&nbsp;&nbsp;" + Q[O].community.name + "</span><span>" + Q[O].roomnum + "室" + Q[O].hallnum + "厅&nbsp;&nbsp;" + Q[O].renttype + "<i><b>" + Math.round(Q[O].price) + "</b>元/月</i></span></span></a>";
							}
						} else {
							if (z === "loupan") {
								var U = "",
								R = I.recomofview,
								V = I.total,
								P = R.length,
								L, N, W, F;
								for (var O = 0; O < P; O++) {
									switch (R[O].status_sale) {
									case 1:
										L = "工地楼盘";
										break;
									case 2:
										L = "即将开盘";
										break;
									case 3:
										L = "期房";
										break;
									case 4:
										L = "现房";
										break;
									case 5:
										L = "已售罄";
										break;
									case 6:
										L = "尾盘";
										break;
									case 7:
										L = "待售";
										break;
									}
									if (R[O].price == 0) {
										N = "售价待定";
									} else {
										N = "<b>" + Math.round(R[O].price) + "</b>元/平";
									}
									W = R[O].loupan_name;
									F = "/" + J.site.info.cityAlias + "/loupan/" + R[O].loupan_id + "/?from=loupan_recomm";
									if ( !! R[O].tuangou.tuangou_id) {
										W += '<em class="Gbt_v"></em>';
										F += "&v=1";
									}
									if ( !! R[O].kft.kft_id) {
										W += '<em class="Gbk"></em>';
									}
									U += '<a data-trace="{Xinfang_Loupan_View:' + R[O].loupan_id + '}" class="lv c53"  href="' + F + t + '" data-city="' + J.site.info.cityAlias + '" data-id="' + R[O].loupan_id + '"><img src=' + x + " data-src=" + R[O].default_image + '><span class="lv1"><span>' + W + "</span><span>" + R[O].sub_region_title + "&nbsp;&nbsp;" + R[O].address + "</span><span>" + L + "<i>" + N + "</i></span></span></a>";
								}
							} else {
								if (z == "sp/shou" || z == "sp/zu" || z == "xzl/shou" || z == "xzl/zu") {
									var U = "",
									R = I.recomofview,
									V = I.total,
									E = "",
									N, H, M;
									switch (z) {
									case "sp/shou":
										M = "shou_sp";
										break;
									case "sp/zu":
										M = "zu_sp";
										break;
									case "xzl/shou":
										M = "shou_xzl";
										break;
									case "xzl/zu":
										M = "zu_xzl";
										break;
									}
									for (O = 0; O < R.length; O++) {
										if (z == "sp/shou" || z == "sp/zu") {
											E = R[O].management;
										} else {
											E = R[O].address;
										}
										if (z == "sp/shou" || z == "xzl/shou") {
											N = R[O].total_price;
											H = R[O].total_price_unit.replace("元", "");
										} else {
											N = parseInt(R[O].unit_price);
											H = R[O].unit_price_unit;
										}
										if (R[O].upper_pic == "") {
											R[O].upper_pic = J.site.info.includePrefix + "/touch/img/default_img.jpg";
										}
										U += '<a data-trace="{Exp_' + M + "_Recommend:" + R[O].house_id + '}" class="lv c53" href="/' + J.site.info.cityAlias + "/" + z + "/" + R[O].house_id + "?from=" + M + "_recomm&isauction=" + R[O].isauction + t + '"><img src="' + J.site.info.includePrefix + '/touch/img/nopic_list.png" data-src="' + R[O].upper_pic + '"><span class="lv1"><span>' + R[O].title + "</span><span>" + R[O].block_name + "&nbsp;&nbsp;" + E + "</span><span>" + parseInt(R[O].area_num) + "平米<i><b>" + N + "</b>" + H + "</i></span></span></a>";
									}
								}
							}
						}
					}
					if (V >= 5) {
						J.g("recoMore").show();
					} else {
						J.g("recoMore").hide();
					}
					if (V > 3) {
						J.g("chanBatch").show();
					} else {
						J.g("chanBatch").hide();
					}
					var S = J.g("nearbyComm");
					S && S.up(1).eq(0).setStyle({
						display: "block"
					});
					S && S.html(K);
					var D = J.g("Rec_content");
					D.html(U);
					lazyLoad.refresh();
					T.exposure.add(D.s("a"));
					S && T.exposure.add(S.s("a"));
				},
				onFailure: function(D) {
					J.logger.log(D);
				}
			});
		}
		window.addEventListener("orientationchange",
		function() {
			J.g("view_intro_frame") && j();
		});
		J.g("view_intro_frame") && j();
		T.bindMobile();
		if (g.hasOwnProperty(C)) {
			g[C]();
		}
		c();
		J.g("viewCollect") && J.g("viewCollect").attr("textVal", J.g("viewCollect").html());
		r();
		a.init();
		T.sharePop().init();
	}
	function i(x, e) {
		var y = "";
		if (x.indexOf("nopic_150x113.gif") < 0 && x != "") {
			y = x;
		} else {
			y = e;
		}
		return y;
	}
	try {
		s();
	} catch(u) {
		J.logger.log(u, "ViewError");
	}
	function c() {
		J.g("prop_collect") && J.g("prop_collect").hide();
		J.g("tel") && J.g("tel").hide();
		J.g("send_message") && J.g("send_message").hide();
		var e = false;
		J.g(document).on("touchstart",
		function() {
			if (!e) {
				e = true;
				J.g("prop_collect") && J.g("prop_collect").show();
				J.g("tel") && J.g("tel").show();
				J.g("send_message") && J.g("send_message").show();
			}
		});
	}
	function f() {
		var D, x, B = J.g("baiduMap"),
		z = parseInt(B.getStyle("width")) * window.devicePixelRatio,
		e = 150 * window.devicePixelRatio,
		C = B.attr("lat"),
		y = B.attr("lng"),
		A = B.attr("prefix");
		z = z > 1024 ? 1024 : z;
		e = e > 1024 ? 1024 : e;
		D = "http://api.map.baidu.com/staticimage?center=" + y + "," + C + "&markers=" + y + "," + C + "&width=" + z + "&height=" + e + "&markerStyles=l&zoom=18";
		x = B.attr("default");
		J.g("baiduMap").html("<img class='mapimg' src=" + x + " data-src='" + D + "'>");
	}
	function k() {
		var x, A = J.g("baiduMap_kft"),
		E = parseInt(A.getStyle("width")) * window.devicePixelRatio,
		y = 197 * window.devicePixelRatio,
		z = A.attr("lnglat"),
		C = A.attr("prefix");
		E = E > 1024 ? 1024 : E;
		y = y > 1024 ? 1024 : y;
		var D = new Array();
		D = z.split("|");
		var B = T.getMapShow(D, "baiduMap_kft");
		var e = B.lng + "," + B.lat;
		x = "http://api.map.baidu.com/staticimage?center=" + e + "&markers=" + z + "&width=" + E + "&height=" + y + "&markerStyles=-1";
		src_default = A.attr("default");
		J.g("baiduMap_kft").html("<img class='mapimg' src=" + src_default + " data-src='" + x + "'>");
	}
	function p(y) {
		if (J.g("swipe")) {
			J.g("swipe").show();
			d(0);
			var x = J.g("swipe").get(),
			e = J.g("positions").s("em");
			mySwipe = Swipe(x, {
				callback: function(z, B) {
					var A = e.length;
					if (A > z) {
						while (A--) {
							e.eq(A).attr("class", "");
						}
						e.eq(z).attr("class", "on");
					}
					d(z);
					T.trackEvent("Track_" + y + "_Swipe", '{"index":"' + (z + 1) + '"}');
				}
			});
		}
	}
	function d(y) {
		var x = J.g("swipe").s("img").eq(y);
		if (x) {
			var B = x.attr("data-src-swipe");
			if (!B) {
				return;
			}
			var A = B.split("/").pop().match(/\d+x\d+/g);
			var e = parseInt(x.getStyle("width")),
			z = parseInt(x.getStyle("height"));
			if (window.devicePixelRatio && window.devicePixelRatio > 1) {
				e = parseInt(e * (window.devicePixelRatio - 0.5));
				z = parseInt(z * (window.devicePixelRatio - 0.5));
			}
			B = B.replace(A, e + "x" + z);
			B && x.attr("src", B).attr("data-src-swipe", "");
		}
	}
	function m(x, e) {
		var y = J.g("tg_tuangou_tele");
		if (!y) {
			return;
		}
		y.on("click",
		function() {
			J.g("tg_phoneText").get().blur();
			var z = J.g("tg_phoneText").val().replace(/(^\s*)|(\s*$)/g, "");
			if (z == "") {
				alert("请输入手机号！");
				return false;
			} else {
				if (/^1(3|5|8|4)[0-9]{9}$/.test(z)) {
					var A = J.g("error_message_text").attr("tgid");
					J.g("error_message_text").setStyle({
						display: "none"
					});
					J.get({
						url: "/" + J.site.info.cityAlias + "/tuangouJoin/?register_from=" + e + "&phone=" + z + "&tuangou_id=" + A + "",
						type: "json",
						onSuccess: function(B) {
							q(B, x);
						},
						onFailure: function(B) {
							J.logger.log(B);
						}
					});
				} else {
					J.g("error_message_text").setStyle({
						display: "inline-block"
					});
				}
			}
		});
		J.g("tg_phoneText").on("focus",
		function() {
			this.value = "";
			J.g("error_message_text").setStyle({
				display: "none"
			});
		});
	}
	function q(x, A) {
		var B = J.g("join_num_view"),
		e = B.attr("data-join"),
		C = J.g("tg_pop_box"),
		y = J.g("tg_view_pop_up_box");
		if (x.status === "true") {
			T.trackEvent(A);
			var z = parseInt(e) + 1;
			B.html(z + "人已报名");
			B.attr("data-join", z);
			y.html("报名成功!");
			y.removeClass("failed");
			y.addClass("success");
		} else {
			if (x.error_message == "已参加") {
				y.removeClass("failed");
				y.addClass("success");
			} else {
				y.removeClass("success");
				y.addClass("failed");
			}
			y.html(x.error_message);
		}
		var E = (parseInt(document.body.clientWidth) - 100) / 2 + "px";
		var D = "45%";
		C.setStyle({
			left: E
		});
		C.setStyle({
			top: D
		});
		C.setStyle({
			display: "inline-block"
		});
		setTimeout(function() {
			J.g("tg_phoneText").val("");
			C.setStyle({
				display: "none"
			});
		},
		2000);
	}
	function v() {
		J.g("floorW") && J.g("floorW").s(".l5").each(function(x, e) {
			e.on("click",
			function() {
				if (e.hasClass("lon")) {
					var y = J.g("floorW").attr("tag"),
					A = J.g("floorW").attr("num");
					J.g(this).removeClass("lon");
					J.g("sellP") && J.g("sellP").html(y);
					J.g("sellN") && J.g("sellN").html(A);
				} else {
					T.trackEvent("Track_fang_floor_select");
					J.g("floorW").s(".l5").each(function(D, C) {
						C.removeClass("lon");
					});
					J.g(this).addClass("lon");
					var z = J.g(this).attr("tag"),
					B = J.g(this).attr("num");
					J.g("sellP") && J.g("sellP").html(z);
					J.g("sellN") && J.g("sellN").html(B);
				}
			});
		});
		J.g("lcMore") && J.g("lcMore").on("click",
		function() {
			T.trackEvent("Track_fang_floor_more");
			J.s(".l4").each(function(x, e) {
				e.removeClass("l4");
			});
			J.g(this).remove();
		});
	}
	function l() {
		var y = J.g("sellPoint") && J.g("sellPoint").s("dl").length,
		e = J.g("sell_open"),
		x = J.g("sell_close");
		if (y && y > 3) {
			e.show();
		}
		e && e.on("click",
		function() {
			T.trackEvent("track_fang_open");
			J.g("sellPoint").s("dl").each(function(A, z) {
				e.hide();
				if (A > 2) {
					z.setStyle({
						display: "block"
					});
				}
				x.show();
			});
		});
		x && x.on("click",
		function() {
			T.trackEvent("track_fang_closed");
			J.g("sellPoint").s("dl").each(function(A, z) {
				x.hide();
				if (A > 2) {
					z.setStyle({
						display: "none"
					});
				}
				e.show();
			});
		});
	}
	function j() {
		var A = J.g("view_introduction"),
		x = J.g("view_open_close"),
		e = J.g("view_intro_frame"),
		z = navigator.userAgent,
		y = e.attr("type");
		if (A && parseInt(A.getStyle("height")) > 120) {
			A.addClass("max_h");
			x.show();
			if (z.match(/MI/)) {
				x.on("click",
				function() {
					B();
				});
			} else {
				e.on("click",
				function() {
					B();
				});
			}
			function B() {
				if (A.hasClass("max_h")) {
					T.trackEvent("track_" + y + "_open");
					A.removeClass("max_h");
					x.addClass("c2s").html("收起");
				} else {
					T.trackEvent("track_" + y + "_closed");
					A.addClass("max_h");
					x.removeClass("c2s").html("展开");
					window.scrollTo(0, A.offset().y - 40);
				}
			}
		}
	}
	function n(D, y, z, A) {
		if (A == "loupan") {
			var x = "楼盘";
		} else {
			if (A == "business") {
				var x = "商铺";
			} else {
				if (A == "office") {
					var x = "写字楼";
				} else {
					var x = "房源";
				}
			}
		}
		var H = J.s(".d1").eq(0),
		e,
		F,
		G;
		if (J.g("prop_f_pop").attr("favorited") == "false") {
			c2 = "☆",
			c3 = "☆ 收藏" + x;
		} else {
			c2 = "★",
			c3 = "★ 已收藏";
		}
		if (J.g("prop_f_pop").attr("device") != "ios") {
			var E = "",
			C = "96%";
		} else {
			var E = "line-height:40px;background-position:0px 12px;",
			C = "96%";
		}
		H.append(e = J.create("div", {
			"class": "userpeo_f",
			id: "prop_collect"
		}).html('<i class="a7 m_w" style="' + E + '">' + c3 + "</i>").on("click",
		function() {
			G = 0;
			B(G);
		}));
		J.g("viewCollect") && J.g("viewCollect").html(c3).on("click",
		function() {
			G = 1;
			B(G);
		});
		if (!J.g("tel") && !J.g("send_message")) {
			e.setStyle({
				width: C
			});
		}
		function B(I) {
			var K = J.s(".H").eq(0).attr("islogin");
			if (K == "false") {
				var L = J.g("prop_f_pop"),
				M = J.g("prop_collect").s("i").eq(0),
				N = J.g("viewCollect");
				if (L.attr("favorited") == "false") {
					L.attr("favorited", "true");
					T.setCookie(D, y);
					L.attr("class", "d3 m_f_w").html("").html("★<br>收藏成功!").show();
					M.attr("class", "a7 m_w").html("").html("★ 已收藏");
					N.html("★ 已收藏");
					T.trackEvent(D.replace("ids", "") + "_collection", "{'tag':'" + I + "'}");
				} else {
					L.attr("favorited", "false");
					L.attr("class", "d3 m_f_w").html("").html("☆<br>取消收藏").show();
					M.attr("class", "a7 m_w").html("").html("☆ 收藏" + x);
					N.html("☆ 收藏" + x);
					J.get("/cookieservice/delete?type=" + D + "&ids=" + z);
					T.trackEvent(D.replace("ids", "") + "_cancel", "{'tag':'" + I + "'}");
				}
				window.scrollY > 1 ? window.scrollTo(0, window.scrollY - 1) : window.scrollTo(0, window.scrollY + 1);
				if (F) {
					clearTimeout(F);
				}
				F = setTimeout(function() {
					L.hide();
				},
				2000);
			} else {
				w(D, I);
			}
		}
	}
	function h() {
		J.g("freeTel") && J.g("freeTel").on("click",
		function() {});
	}
	function r() {
		var e = J.g("view_favorite");
		if (!e) {
			return false;
		}
		J.get({
			url: "/cookieservice/favoritecount",
			onSuccess: function(x) {
				e.html("★&nbsp;我的收藏(" + x + ")");
			},
			onFailure: function(x) {
				J.logger.log(x);
			}
		});
	}
	function b() {
		var e = J.g("recomm"),
		z = e.attr("type"),
		y = e.attr("tag"),
		x = e.attr("data-src"),
		A = e.attr("data-nopic");
		J.g("chanBatch") && J.g("chanBatch").on("click",
		function() {
			var B = document.getElementById("chanNumber") && document.getElementById("chanNumber").value++;
			J.get({
				url: "/" + J.site.info.cityAlias + "/" + z + "/recommend/" + y + "?change=" + B,
				data: {
					TP: "RECOMM"
				},
				type: "json",
				timeout: 15000,
				onSuccess: function(Q) {
					if (JSON.stringify(Q).replace(/\s/ig, "") == "") {
						return;
					}
					if (z == "sale") {
						var M = "",
						L = Q.recomofview,
						C;
						for (H = 0; H < L.length; H++) {
							if (L[H].IMAGESRC.indexOf("nopic_150x113.gif") < 0 && L[H].IMAGESRC != "") {
								C = L[H].IMAGESRC;
							} else {
								C = A;
							}
							M += '<a data-trace="{Exp_Recommend:' + L[H].PROID + '}" class="lv prop_rec_list c53"  href="/' + J.site.info.cityAlias + "/sale/" + L[H].PROID + "/?from=prop_recomm_rotate&isauction=" + L[H].isauction + t + '" data-isa="' + L[H].isauction + '" data-city="' + J.site.info.cityAlias + '" data-id="' + L[H].PROID + '"><img src=' + x + " data-src=" + C + '><span class="lv1"><span>' + L[H].TITLE + "</span><span>" + L[H].AREANAME + "&nbsp;&nbsp;" + L[H].COMMNAME + "</span><span>" + L[H].ROOMNUM + "室" + L[H].HALLNUM + "厅&nbsp;&nbsp;" + Math.round(L[H].AREANUM) + "平米<i><b>" + Math.round(L[H].PROPRICE) + "</b>万</i></span></span></a>";
						}
						T.trackEvent("track_recomm_nextpage");
					} else {
						if (z == "rent") {
							var M = "",
							L = Q.recomofview,
							C;
							for (H = 0; H < L.length; H++) {
								if (L[H].photo.indexOf("nopic_150x113.gif") < 0 && L[H].photo != "") {
									C = L[H].photo;
								} else {
									C = A;
								}
								M += '<a data-trace="{Exp_Recommend:' + L[H].id + '}" class="lv prop_rec_list c53"  href="/' + J.site.info.cityAlias + "/rent/" + L[H].id + "-" + L[H].type + "/?from=rent_recomm_rotate&isauction=" + L[H].PROTYPE + t + '" data-isa="' + L[H].isauction + '" data-city="' + J.site.info.cityAlias + '" data-id="' + L[H].id + "-" + L[H].type + '"><img src=' + x + " data-src=" + C + '><span class="lv1"><span>' + L[H].title + "</span><span>" + L[H].block.name + "&nbsp;&nbsp;" + L[H].community.name + "</span><span>" + L[H].roomnum + "室" + L[H].hallnum + "厅&nbsp;&nbsp;" + L[H].renttype + "<i><b>" + Math.round(L[H].price) + "</b>元/月</i></span></span></a>";
							}
							T.trackEvent("track_rent_recomm_nextpage");
						} else {
							if (z == "loupan") {
								var M = "",
								L = Q.recomofview,
								K = L.length,
								F, I, E, D;
								for (var H = 0; H < K; H++) {
									switch (L[H].status_sale) {
									case 1:
										F = "工地楼盘";
										break;
									case 2:
										F = "即将开盘";
										break;
									case 3:
										F = "期房";
										break;
									case 4:
										F = "现房";
										break;
									case 5:
										F = "已售罄";
										break;
									case 6:
										F = "尾盘";
										break;
									case 7:
										F = "待售";
										break;
									}
									if (L[H].price == 0) {
										I = "售价待定";
									} else {
										I = "<b>" + Math.round(L[H].price) + "</b>元/平";
									}
									E = L[H].loupan_name;
									D = "/" + J.site.info.cityAlias + "/loupan/" + L[H].loupan_id + "/?from=loupan_recomm_rotate";
									if ( !! L[H].tuangou.tuangou_id) {
										E += '<em class="Gbt_v"></em>';
										D += "&v=1";
									}
									if ( !! L[H].kft.kft_id) {
										E += '<em class="Gbk"></em>';
									}
									M += '<a data-trace="{Exp_Recommend:' + L[H].loupan_id + '}" class="lv c53"  href="' + D + t + '" data-city="' + J.site.info.cityAlias + '" data-id="' + L[H].loupan_id + '"><img src=' + x + " data-src=" + L[H].default_image + '><span class="lv1"><span>' + E + "</span><span>" + L[H].sub_region_title + "&nbsp;&nbsp;" + L[H].address + "</span><span>" + F + "<i>" + I + "</i></span></span></a>";
								}
								T.trackEvent("track_recomm_nextpage");
							} else {
								if (z == "sp/shou" || z == "sp/zu" || z == "xzl/shou" || z == "xzl/zu") {
									var M = "",
									L = Q.recomofview,
									P = "",
									I, O, N;
									switch (z) {
									case "sp/shou":
										N = "shou_sp";
										break;
									case "sp/zu":
										N = "zu_sp";
										break;
									case "xzl/shou":
										N = "shou_xzl";
										break;
									case "xzl/zu":
										N = "zu_xzl";
										break;
									}
									for (H = 0; H < L.length; H++) {
										if (z == "sp/shou" || z == "sp/zu") {
											P = L[H].management;
										} else {
											P = L[H].address;
										}
										if (z == "sp/shou" || z == "xzl/shou") {
											I = L[H].total_price;
											O = L[H].total_price_unit.replace("元", "");
										} else {
											I = parseInt(L[H].unit_price);
											O = L[H].unit_price_unit;
										}
										if (L[H].upper_pic == "") {
											L[H].upper_pic = J.site.info.includePrefix + "/touch/img/default_img.jpg";
										}
										M += '<a data-trace="{Exp_' + N + "_Recommend:" + L[H].house_id + '}" class="lv c53" href="/' + J.site.info.cityAlias + "/" + z + "/" + L[H].house_id + "?from=" + N + "_recomm_rotate&isauction=" + L[H].isauction + t + '"><img src="' + J.site.info.includePrefix + '/touch/img/nopic_list.png" data-src="' + L[H].upper_pic + '"><span class="lv1"><span>' + L[H].title + "</span><span>" + L[H].block_name + "&nbsp;&nbsp;" + P + "</span><span>" + parseInt(L[H].area_num) + "平米<i><b>" + I + "</b>" + O + "</i></span></span></a>";
									}
									T.trackEvent("track_" + N + "_recomm_nextpage");
								}
							}
						}
					}
					var G = J.g("Rec_content");
					G.html(M);
					lazyLoad.refresh();
					T.exposure.add(G.s("a"));
				},
				onFailure: function(C) {
					J.logger.log(C);
				}
			});
		});
	}
	function o() {
		var A = location.pathname.match(/\/\d+/)[0].replace("/", "").toString().toLowerCase();
		var z = location.pathname.split("/");
		var e = ((z.length > 2) ? z[2] : "").toString().toLowerCase();
		var y = J.g("prop_favorite");
		var x;
		switch (e) {
		case "sale":
			x = "fid=" + A + "&type=anjuke";
			break;
		case "loupan":
			x = "fid=" + A + "&type=loupan";
			break;
		case "fang":
			x = "fid=" + A + "&type=fang";
			break;
		case "rent":
			x = "fid=" + A + "&type=haozu-" + location.pathname.match(/-\d/)[0].replace("-", "");
			break;
		case "sp":
			if (z[3] == "shou") {
				x = "fid=" + A + "&type=pbusiness";
			} else {
				if (z[3] == "zu") {
					x = "fid=" + A + "&type=rbusiness";
				}
			}
			break;
		case "xzl":
			if (z[3] == "shou") {
				x = "fid=" + A + "&type=poffice";
			} else {
				if (z[3] == "zu") {
					x = "fid=" + A + "&type=roffice";
				}
			}
			break;
		default:
			break;
		}
		return x;
	}
	function w(y, e) {
		var A = o();
		var z = J.g("prop_f_pop");
		var x;
		if (z.attr("favorited") == "false") {
			T.trackEvent(y.replace("ids", "") + "_collection", "{'tag':'" + e + "'}");
			J.get({
				url: "/favorites/add?" + A,
				data: {},
				timeout: 15000,
				onSuccess: function(B) {
					if (B == "true") {
						z.attr("favorited", "true").html("<span>★</span>收藏成功!").show();
						J.g("viewCollect").html("★ 已收藏");
						J.g("prop_collect").html("★ 已收藏");
						x = setTimeout(function() {
							z.hide();
							clearTimeout(x);
						},
						2000);
					} else {
						J.logger.log("添加收藏失败");
					}
				},
				onFailure: function(B) {
					J.logger.log(B);
				}
			});
		} else {
			if (z.attr("favorited") == "true") {
				T.trackEvent(y.replace("ids", "") + "_cancel", "{'tag':'" + e + "'}");
				J.get({
					url: "/favorites/delete?" + A,
					data: {},
					timeout: 15000,
					onSuccess: function(B) {
						if (B == "true") {
							z.attr("favorited", "false").html("").html("<span>☆</span>取消收藏").show();
							J.g("viewCollect").html(J.g("viewCollect").attr("textVal"));
							J.g("prop_collect").html(J.g("viewCollect").attr("textVal"));
							x = setTimeout(function() {
								z.hide();
								clearTimeout(x);
							},
							2000);
						} else {
							J.logger.log("删除收藏失败");
						}
					},
					onFailure: function(B) {
						J.logger.log(B);
					}
				});
			}
		}
	}
})();