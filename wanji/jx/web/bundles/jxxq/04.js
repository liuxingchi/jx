(function(k) {
	var f = {},
	B = document,
	A = J.site.info,
	g = A.cityAlias || "sh",
	n, o, l, h, b = 0,
	F = false,
	H;
	function C() {
		var w = {
			autoStart: false,
			pageName: "Exp_" + J.site.info.pageName,
			site: "m_anjuke-npv",
			trackType: "get"
		},
		d = new J.ui.exposure(w);
		d.start();
		return d;
	}
	function E(K) {
		var L = (K && K.offsetY) || 100,
		d;
		function I(M) {
			var P = M.attr("data-src"),
			O = P.split("/").pop().match(/\d+x\d+/g);
			if (u = M.attr("src")) {
				M.on("error",
				function() {
					var Q = parseInt(M.getStyle("width")),
					R = parseInt(M.getStyle("height"));
					P = P.replace(d, Q + "x" + R);
					M.attr("src", P).attr("data-src", "");
					M.un("error");
					M.on("error",
					function() {
						M.attr("src", u);
					});
				});
			}
			var N = new Image();
			N.src = P;
			N.onload = function() {
				if (!O) {
					M.attr("src", P).attr("data-src", "");
				} else {
					var Q = parseInt(M.getStyle("width")),
					R = parseInt(M.getStyle("height"));
					if (window.devicePixelRatio && window.devicePixelRatio != 1) {
						Q = parseInt(Q * window.devicePixelRatio);
						R = parseInt(R * window.devicePixelRatio);
					}
					d = Q + "x" + R;
					if (Q && R) {
						P = P.replace(O, Q + "x" + R);
					}
					M.attr("src", P).attr("data-src", "");
				}
			};
		}
		function w(N) {
			var M = [],
			O = k.scrollY + k.innerHeight + L;
			J.g(B).s("img").each(function(Q, P) {
				if (P.offset().y < O) {
					if (!P.attr("data-src")) {
						return;
					}
					I(P);
					return;
				}
			});
		}
		w();
		this.refresh = w;
		J.g(B).on("scroll", w);
	}
	function t(I) {
		var M = I.attr("src"),
		L = M.split("/").pop().match(/\d+x\d+/g);
		var d = parseInt(I.getStyle("width")),
		K = parseInt(I.getStyle("height"));
		if (window.devicePixelRatio && window.devicePixelRatio != 1) {
			d = parseInt(d * window.devicePixelRatio);
			K = parseInt(K * window.devicePixelRatio);
		}
		size = d + "x" + K;
		if (L) {
			if (d && K) {
				M = M.replace(L, d + "x" + K);
			}
		}
		I.attr("src", M);
	}
	function q(I, K) {
		var w = "http://api.anjuke.com/common/cookie/add/guid/",
		d;
		w += J.getCookie(J.site.cookies.guid);
		w += "?" + I + "=" + K;
		d = new Image();
		d.src = w;
	}
	function G(I) {
		var w = I ? "margin-bottom:" + I + "px": "",
		d = J.create("a", {
			id: "go_top",
			"class": "top",
			style: "display:none;" + w,
			onclick: 'window.scrollTo(0,0); this.style.display="none";'
		});
		d.appendTo(J.g("body"));
		window.onscroll = function() {
			if (document.body.scrollTop > window.screen.availHeight) {
				d && d.setStyle({
					display: "block"
				});
			} else {
				d && d.setStyle({
					display: "none"
				});
			}
		};
	}
	function y(w) {
		var K, I;
		Q(w);
		var O = new J.ui.searchHistory({
			eleId: "searchPage",
			onShow: function() {
				J.s(".sousuo").eq(0).hide();
				J.g("searchPage").up(0).setStyle({
					display: "block"
				});
				if (d = N.s(".autocomplete_def").eq(0)) {
					d.html("");
					d.up().setStyle({
						position: "static"
					});
				}
				J.g("noResult") && J.g("noResult").hide();
				L();
			},
			onHide: function() {
				J.s(".sousuo").eq(0).show();
				J.g("searchPage").up(0).setStyle({
					display: "none"
				});
			},
			onTapAction: function(ac) {
				S();
				location.href = "/" + g + "/" + K + "/?q=" + ac;
			}
		});
		var ab, U = "",
		N = J.g("his_content"),
		M = J.g("hisSearch"),
		X,
		d,
		Y = J.g("hotSearch"),
		aa = J.g("searchTab"),
		Z = aa.s("span"),
		T = J.g("noResult");
		V();
		if (K == "qalist/search" || K == "sale") {
			W();
		}
		function V() {
			ab = O.getHistory();
			for (var ac = 0; ac < ab.length; ac++) {
				U = U + "<span>" + ab[ac] + "</span>";
			}
			U = "<div>" + U + "</div>";
			if (N) {
				M.html(U);
				U = "";
			}
			if (ab.length != 0) {
				N.up().removeClass("s_No_Data");
				M.s("span").each(function(ae, ad) {
					ad.on("click",
					function() {
						f.trackEvent("trackEvent_on_tap_history");
						O.setHistory(ad.html());
						location.href = "/" + g + "/" + K + "/?q=" + ad.html();
					});
				});
			} else {
				N.up().addClass("s_No_Data");
			}
		}
		var P = J.g("searchPage").s("input").eq(0);
		P && P.on("input",
		function() {
			if (P.val() != "") {
				X = P.val();
				M.hide();
				aa && aa.hide();
				Y && Y.hide();
				T && T.hide();
			} else {
				J.g("noResult") && J.g("noResult").hide();
				if (K == "qalist/search" || K == "sale") {
					aa && aa.show();
					if (aa.s("span").eq(0).hasClass("search_on")) {
						M.hide();
						Y && Y.show();
					} else {
						M.show();
						Y && Y.hide();
					}
				} else {
					aa && aa.hide();
				}
			}
		});
		function R(ac) {
			ac = ac.replace(/&amp;/g, "&");
			ac = ac.replace(/&quot;/g, "");
			ac = ac.replace(/&lt;/g, "<");
			ac = ac.replace(/&gt;/g, ">");
			return ac;
		} (function() {
			J.g("searchInput") && P.val(J.g("searchInput").val());
			P.autocomplete({
				autoSubmit: false,
				width: J.page.viewWidth(),
				boxTarget: function() {
					return N;
				},
				itemBuild: function(af) {
					if (K != "qalist/search") {
						var ad = af.name;
						ac = af.name.replace(X, '<em style="color:#999999">' + X + "</em>");
					} else {
						var ad = "",
						ac = "",
						ag, ae = "";
						if (!af.qtitle) {
							return false;
						}
						if (af.queclass && af.queclass.length > 0) {
							ag = af.queclass[0].name ? af.queclass[0].name: "";
						} else {
							ag = "";
						}
						if (!af.answer_num) {
							af.answer_num = 0;
						}
						ae = R(af.qtitle);
						if (af.is_baike == "yes") {
							ad = "<b>" + R(af.qtitle) + "</b>";
						} else {
							ad = "<b>" + R(af.qtitle) + "</b><i>" + ag + "&nbsp;" + af.answer_num + "回答</i>";
						}
						ac = ad.replace(X, '<em style="color:#999999">' + X + "</em>");
					}
					return {
						l: ac,
						v: ae
					};
				},
				onSelect: function(ac) {
					S();
					f.trackEvent("trackEvent_on_tap_associate");
					if (K != "qalist/search") {
						O.setHistory(ac.name);
						location.href = "/" + g + "/" + K + "/?q=" + ac.name;
					} else {
						O.setHistory(R(ac.qtitle));
						if (ac.is_baike == "yes") {
							location.href = "/qa/baike/" + ac.qid;
						} else {
							location.href = "/qa/view/" + ac.qid;
						}
					}
				},
				source: function(ac, ad) {
					if (ac.q === "") {
						return ad([]);
					}
					J.get({
						url: I + ac.q,
						type: "json",
						onSuccess: function(ae) {
							if (!ae.length && K == "qalist/search") {
								T.show();
							} else {
								T.hide();
							}
							ad(ae);
						}
					});
				},
				onChange: function(ac) {
					if (K != "qalist/search") {
						P.val(ac.name);
					} else {
						P.val(R(ac.qtitle));
					}
				}
			});
		}.require("ui.autocomplete"));
		J.g("hotSearch") && J.g("hotSearch").s("a").each(function(ad, ac) {
			ac.on("click",
			function() {
				S();
			});
		});
		J.g("hisSearch") && J.g("hisSearch").s("span").each(function(ad, ac) {
			ac.on("click",
			function() {
				S();
			});
		});
		function S() {
			setTimeout(function() {
				O.hidePage();
			},
			800);
		}
		if (spTab = J.g("spTab")) {
			spTab.s("span").each(function(ad, ac) {
				ac.on("click",
				function() {
					if (!ac.hasClass("active")) {
						spTab.s("span").each(function(af, ae) {
							ae.removeClass("active");
						});
						ac.addClass("active");
						Q("shangye" + (ad + 1));
						O.changeTag("shangye" + (ad + 1));
						V();
					}
				});
			});
		}
		function Q(ac) {
			switch (ac) {
			case "Trendency_Comm_Details":
			case "Trendency_Comm":
				K = "trendency/community/details";
				I = "/" + g + "/anjuke/suggest/?q=";
				break;
			case "Anjuke_Home":
			case "Anjuke_Prop_List":
				K = "sale";
				I = "/" + g + "/anjuke/suggest/?q=";
				break;
			case "Xinfang_Fangyuan":
			case "Xinfang_Fangyuan_List":
				K = "fang";
				I = "/" + g + "/xinfang/suggest/?q=";
				break;
			case "Xinfang_Home":
			case "Xinfang_Loupan_List":
				K = "loupan";
				I = "/" + g + "/xinfang/suggest/?q=";
				break;
			case "Rental_Home":
			case "Rent_List":
				K = "rent";
				I = "/" + g + "/rent/suggest/?q=";
				break;
			case "Business_Home":
			case "shangye1":
			case "Purchase_Business_List":
				K = "sp/shou";
				I = "/" + g + "/business/suggest/?type=4&q=";
				break;
			case "shangye2":
			case "Rental_Business_List":
				K = "sp/zu";
				I = "/" + g + "/business/suggest/?type=3&q=";
				break;
			case "shangye3":
			case "Purchase_Office_List":
				K = "xzl/shou";
				I = "/" + g + "/business/suggest/?type=2&q=";
				break;
			case "shangye4":
			case "Rental_Office_List":
				K = "xzl/zu";
				I = "/" + g + "/business/suggest/?type=1&q=";
				break;
			case "Qa_Home":
			case "Qa_Category_List":
			case "Qa_Search_List":
				K = "qalist/search";
				I = "/" + g + "/qa/suggest/?q=";
				break;
			default:
				break;
			}
		}
		J.g("searchInput") && J.g("searchInput").on("focus",
		function() {
			var ac = J.g("searchHead");
			if (document.body.scrollTop < 2) {
				ac && ac.setStyle({
					position: "fixed"
				});
			} else {
				ac && ac.setStyle({
					position: "absolute"
				});
			}
			O.showPage();
		});
		J.g("search") && J.g("search").on("click",
		function() {
			location.href = "/" + g + "/" + K;
		});
		function L() {
			J.g("searchInput").get().blur();
			if (K == "qalist/search" || K == "sale") {
				M.hide();
				aa && aa.show();
				Y && Y.show();
				Z.eq(0).addClass("search_on");
				Z.eq(1).removeClass("search_on");
				if (K == "sale" && M.s("span").length) {
					Y.hide();
					M.show();
					Z.eq(1).addClass("search_on");
					Z.eq(0).removeClass("search_on");
				}
			} else {
				M.show();
				aa && aa.hide();
				Y && Y.hide();
			}
		}
		function W() {
			if (K == "sale") {
				Y.s("div").eq(0).hide();
				Y.s("div").eq(1).show();
			}
			aa && aa.show();
			Y && Y.show();
			M && M.hide();
			Z.each(function(ad, ac) {
				ac.on("click",
				function() {
					if (!ac.hasClass("search_on")) {
						if (ad == 0) {
							Y.show();
							M.hide();
							Z.eq(0).addClass("search_on");
							Z.eq(1).removeClass("search_on");
						} else {
							Y.hide();
							M.show();
							Z.eq(1).addClass("search_on");
							Z.eq(0).removeClass("search_on");
						}
					}
				});
			});
		}
		return O.getHistory();
	}
	function D(d) {
		var N;
		var w = J.g("phone_pop_drop");
		var M = J.g("tel");
		if (N = J.g("view_nowcall")) {
			var L = document.body.scrollHeight,
			K = document.documentElement.scrollHeight,
			I = L > K ? L: K;
			w.setStyle({
				height: I + "px",
				display: "block"
			});
			J.g("prop_collect") && J.g("prop_collect").setStyle({
				display: "none"
			});
			M.setStyle({
				display: "none"
			});
			N.setStyle({
				display: "block"
			});
			f.trackEvent(N.attr("data-track-open"));
			setTimeout(function() {
				N.addClass("animation");
				w.un("click");
				w.on("click",
				function(O) {
					O.stop();
					p();
					f.trackEvent(N.attr("data-track-close"));
				});
				N.un("click");
				N.on("click",
				function(O) {
					O.stop();
					p();
					f.trackEvent(N.attr("data-track-close"));
				});
				J.g("view_nowcall").un("click");
				J.g("view_nowcall").on("click",
				function(O) {
					O.stop();
					f.trackEvent(M.attr("data-track-soj"), '{"puid":"' + n + '","phone":"' + o + '","tag":"' + d + '"}');
					F = true;
					document.addEventListener("touchstart", c, false);
					window.addEventListener("popstate", c, o, n);
					p();
					location.href = J.g("view_call").attr("href");
				});
			},
			100);
		} else {
			if (M.attr("phone-ext")) {
				alert("接通后, 需手工拨打分机号:" + M.attr("phone-ext"));
			}
			f.trackEvent(M.attr("data-track-soj"), '{"puid":"' + n + '","phone":"' + o + '","tag":"' + d + '"}');
			F = true;
			document.addEventListener("touchstart", c, false);
			window.addEventListener("popstate", c, o, n);
			location.href = M.attr("href");
		}
	}
	function e() {
		if (!J.g("tel")) {
			return;
		}
		var d = J.g("tel");
		l = f.referrer;
		h = document.location.href;
		o = d.attr("phone");
		n = J.site.createGuid();
		d && d.on("click",
		function(w) {
			w.stop();
			H = 0;
			D(H);
		});
		J.g("contact_call") && J.g("contact_call").on("click",
		function(w) {
			w.stop();
			H = 2;
			D(H);
		});
		J.g("new_phone") && J.g("new_phone").on("click",
		function(w) {
			w.stop();
			H = 1;
			D(H);
		});
	}
	function c() {
		if (!F) {
			return;
		}
		F = false;
		var d = (new Date()).getTime() - b;
		b = 0;
		f.trackEvent(J.g("tel").attr("data-track-soj"), '{"pt":"' + d + '","puid":"' + n + '","phone":"' + o + '","tag":"' + H + '"}', h, l);
		document.removeEventListener("touchstart", c);
		window.removeEventListener("popstate", c);
	}
	function p() {
		J.g("view_nowcall").removeClass("animation");
		setTimeout(function() {
			J.g("view_nowcall").setStyle({
				display: "none"
			});
			J.g("tel").setStyle({
				display: "-webkit-box"
			});
			J.g("prop_collect") && J.g("prop_collect").setStyle({
				display: "-webkit-box"
			});
		},
		400);
		J.g("phone_pop_drop").setStyle({
			display: "none"
		});
	}
	function i(d) {
		var N, O;
		if (J.g("list_pop_box_body")) {
			J.g("list_pop_box_body").remove();
		}
		var M = document.body.scrollHeight,
		L = document.documentElement.scrollHeight,
		K = M > L ? M: L;
		J.create("div", {
			"class": "F",
			id: "list_pop_box_body"
		}).html(J.g(d).html()).appendTo("body");
		N = J.g("list_pop_box_body");
		J.g(d).remove();
		if (!J.g("drop_list")) {
			J.g("list_filter").insertAfter(J.create("div", {
				id: "drop_list"
			}));
			J.g("drop_list").addClass("e7");
		}
		function I() {
			var P = J.s(".e3"),
			Q = J.s(".e4a");
			P && P.each(function(S, R) {
				if (R.hasClass("e3s")) {
					setTimeout(function() {
						R.removeClass("e3s");
					},
					300);
				}
			});
			Q && Q.each(function(S, R) {
				if (R.hasClass("e4b")) {
					setTimeout(function() {
						R.removeClass("e4b");
					},
					300);
				}
			});
		}
		O = J.g("list_filter_closed");
		O.setStyle({
			top: window.innerHeight / 2 + "px"
		});
		window.addEventListener("orientationchange",
		function() {
			O.setStyle({
				top: window.innerHeight / 2 + "px"
			});
		});
		N.setStyle({
			display: "none"
		});
		N.on("click",
		function(P) {
			I();
			P.stop();
			f.trackEvent(J.g("list_filter_closed").attr("data-event"));
			v();
		});
		O.on("click",
		function(P) {
			I();
			P.stop();
			f.trackEvent(J.g("list_filter_closed").attr("data-event"));
			v();
		});
		N.s(".e2a").each(function(Q, P) {
			P.setStyle({
				"-webkit-transform": "translate(0px, 0px) scale(1) translateZ(0px)"
			});
			w(P);
		});
		N.on("touchmove",
		function(P) {
			P.preventDefault();
		});
		function w(U) {
			var P, T, S = 0,
			Q = 0,
			R = 0;
			U.on("touchstart",
			function(V) {
				var X = V.touches[0];
				P = X.pageY;
				var W = U.getStyle("-webkit-transform");
				S = parseInt(W.match(/,\s*(-?\d*px)/i)[1]);
				R = parseInt(U.getStyle("height")) - J.page.viewHeight();
			});
			U.on("touchmove",
			function(V) {
				V.preventDefault();
				var W = V.touches[0];
				T = W.pageY;
				Q = S + T - P;
				U.setStyle({
					"-webkit-transform": "translate(0px, " + Q + "px) scale(1) translateZ(0px)"
				});
			});
			U.on("touchend",
			function(V) {
				var W = U.getStyle("-webkit-transform");
				S = parseInt(W.match(/,\s*(-?\d*px)/i)[1]);
				if (S > 0 || R < 0) {
					S = 0;
					U.setStyle({
						"-webkit-transform": "translate(0px, 0px) scale(1) translateZ(0px)"
					});
				} else {
					if (R < Math.abs(Q)) {
						S = -R;
						U.setStyle({
							"-webkit-transform": "translate(0px, " + S + "px) scale(1) translateZ(0px)"
						});
					}
				}
			});
		}
	}
	function v() {
		if (!J.g("list_filter") || !J.g("list_pop_box_body")) {
			return false;
		}
		var w = J.g("list_pop_box_body");
		var d = J.g("body");
		var I = J.g("list_filter");
		if (I.hasClass("es")) {
			I.removeClass("es");
			setTimeout(function() {
				w.setStyle({
					display: "none"
				});
			},
			500);
			if (document.body.scrollTop > 90) {
				J.g("filterF") && J.g("filterF").setStyle({
					position: "fixed",
					top: "0px",
					zIndex: 99
				});
			}
		} else {
			document.getElementById("drop_list").addEventListener("touchmove",
			function(L) {
				L.preventDefault();
			});
			var K = document.body.scrollTop;
			if (document.body.scrollTop > 90) {
				J.g("filterF") && J.g("filterF").setStyle({
					position: "absolute",
					top: K + "px",
					zIndex: 98
				});
			}
			w.setStyle({
				display: "block"
			});
			setTimeout(function() {
				I.addClass("es");
			},
			100);
		}
	}
	function r(w, d, K) {
		var I = {
			site: "m_anjuke",
			page: w,
			customparam: d
		};
		K && (I.referrer = K);
		J.logger.trackEvent(I);
	}
	function j(w, d) {
		J.logger.trackEvent({
			site: "m_anjuke-npv",
			page: w,
			customparam: d
		});
	}
	function s() {
		var w = /\.com\/\w+\/(?:\?|$|(?:xinfang|loupan|fangyuan|rental|shangye)(?:\/$))/,
		d = /\.com\/\w+\/(?:(?:sale|loupan|fang|rent|(?:sp|xzl)(?:\/(?:zu|shou)))(?:\/(?:[^\d]+|$|\?)))/;
		if (w.test(J.D.location.href) && d.test(J.site.getRef())) {
			J.D.location.reload();
		}
	}
	k.addEventListener("popstate",
	function() {
		var L = J.D,
		K = L.head || L.getElementsByTagName("head")[0],
		d = K.getAttribute("data-page"),
		I = K.getAttribute("data-ppc"),
		w = K.getAttribute("data-back");
		if (w && d) {
			s();
			r(d, '{"B":"1"}', J.site.getRef());
			I && ((new Image()).src = I);
			J.site.setRef(L.location.href);
		}
		K.setAttribute("data-back", "back");
	});
	function z(V, I) {
		if (V.length == 0) {
			return;
		} else {
			if (V.length == 1) {
				var X = parseFloat(V[0].split(",")[0]),
				w = parseFloat(V[0].split(",")[1]),
				d = 18;
				var Z = {
					lng: X,
					lat: w,
					zoom: d
				};
				return Z;
			}
		}
		var R = parseFloat(V[0].split(",")[0]),
		S = parseFloat(V[0].split(",")[0]),
		O = parseFloat(V[0].split(",")[1]),
		P = parseFloat(V[0].split(",")[1]),
		aa,
		N,
		Y,
		L,
		M,
		T,
		W = 0,
		K = 0,
		Q = 0;
		for (var U = 0; U < V.length; U++) {
			aa = parseFloat(V[U].split(",")[0]);
			N = parseFloat(V[U].split(",")[1]);
			if (aa > S) {
				S = aa;
			} else {
				if (aa < R) {
					R = aa;
				}
			}
			if (N > P) {
				P = N;
			} else {
				if (N < O) {
					O = N;
				}
			}
		}
		var X = (S + R) / 2,
		w = (P + O) / 2;
		var Z = {
			lng: X,
			lat: w,
			lng_min: R,
			lng_max: S,
			lat_min: O,
			lat_max: P
		};
		return Z;
	}
	function m(M, w, N, Q, d, R, L) {
		J.create("div", {
			id: "dropMenu"
		}).appendTo(M);
		J.g("dropMenu").addClass("Gdrop_shield");
		var S = "";
		if (Q) {
			S = "<i></i>";
		}
		for (var K = 0; K < d.length; K++) {
			var I = "";
			var P = "";
			if (L) {
				I = L[K];
			}
			if (R) {
				P = R[K];
			}
			S += "<a data=" + I + " href=" + P + ">" + d[K] + "</a>";
		}
		var O = w.offset().y + w.height() + 3 + "px";
		J.create("div", {}).addClass(N).html(S).appendTo(J.g("dropMenu")).setStyle({
			top: O
		});
		J.g("dropMenu").setStyle({
			display: "none"
		});
	}
	function x(K) {
		var O = /^[^\?]+\?([\w\W]+)$/,
		M = /([^&=]+)=([\w\W]*?)(&|$)/g,
		I = O.exec(K),
		w = {};
		if (I && I[1]) {
			var L = I[1],
			d;
			while ((d = M.exec(L)) != null) {
				try {
					w[d[1]] = decodeURI(d[2]);
				} catch(N) {}
			}
		}
		return w;
	}
	function a() {
		var w, P, I, L;
		function M() {
			w = J.g("activityPop");
		}
		function O() {
			if (w) {
				w.get().onclick = function(R) {
					R = window.event || R;
					var Q = J.g(R.target || R.srcElement);
					if (Q.attr("class") == "shareContent") {
						return;
					} else {
						if (Q.get().tagName.toLowerCase() == "a") {
							J.logger.trackEvent({
								site: "anjuke-npv",
								page: "share_touch",
								customparam: '{"stp":"share"}'
							});
							f.trackEvent(K(Q));
						} else {
							d(true);
							w.hide();
							J.g("foot_share_fix").setStyle({
								display: "none"
							});
						}
					}
				};
				w.get().onmousedown = function(R) {
					R = window.event || R;
					var Q = J.g(R.target || R.srcElement);
					if (Q.get().tagName.toLowerCase() == "a" && Q.attr("title") == "分享到新浪微博") {
						if (jiathis_config) {
							jiathis_config.summary = "我已加入安居客拜房神教，虔诚地膜拜了三下，现在正在坐等#马上有房#！你也来试试 http://m.anjuke.com @安居客官方微博";
						}
					} else {
						if (Q.get().tagName.toLowerCase() == "a" && Q.attr("title") != "分享到新浪微博") {
							if (jiathis_config) {
								jiathis_config.summary = "我已加入安居客拜房神教，虔诚地膜拜了三下，现在正在坐等#马上有房#！你也来试试 http://m.anjuke.com";
							}
						}
					}
				};
				w.get().addEventListener("touchmove",
				function(Q) {
					if (Q.preventDefault) {
						Q.preventDefault();
					}
				},
				false);
			}
			window.addEventListener("resize", N, false);
		}
		function N() {
			clearTimeout(P);
			P = setTimeout(function() {
				if (J.s(".V").length > 0) {
					J.s(".V").eq(0).setStyle({
						height: J.page.viewHeight() + "px",
						overflow: "hidden"
					});
				} else {
					J.s("body").eq(0).setStyle({
						height: J.page.viewHeight() + "px",
						overflow: "hidden"
					});
				}
			},
			200);
		}
		function K(S) {
			var R, Q;
			if (S && S.attr("title")) {
				Q = S.attr("title");
				switch (Q) {
				case "复制网址":
					R = "copy";
					break;
				case "分享到QQ空间":
					R = "qq";
					break;
				case "分享到新浪微博":
					R = "xlwb";
					break;
				case "分享到腾讯微博":
					R = "txwb";
					break;
				case "分享到人人网":
					R = "renren";
					break;
				default:
					R = "other";
					break;
				}
				R = "track_indexShare_" + R;
			}
			return R;
		}
		function d(Q) {
			if (Q) {
				if (J.s(".V").length > 0) {
					J.s(".V").eq(0).setStyle({
						height: "auto",
						overflow: "auto"
					});
				} else {
					J.s("body").eq(0).setStyle({
						height: "auto",
						overflow: "auto"
					});
				}
				if (J.s(".H").length > 0) {
					J.s(".H").eq(0).setStyle({
						"z-index": "10000000002"
					});
				}
			} else {
				if (J.s(".V").length > 0) {
					J.s(".V").eq(0).setStyle({
						height: J.page.viewHeight() + "px",
						overflow: "hidden"
					});
				} else {
					J.s("body").eq(0).setStyle({
						height: J.page.viewHeight() + "px",
						overflow: "hidden"
					});
				}
				if (J.s(".H").length > 0) {
					J.s(".H").eq(0).setStyle({
						"z-index": "2"
					});
				}
			}
		}
		return {
			init: function() {
				L = (J.s("body").eq(0).attr("data-page") == "Anjuke_Home") ? true: false;
				if (J.g("activityPop") && L) {
					var Q = new Date();
					var R = Q.getDate() + "/1";
					if (!J.cookie.getCookie("shd")) {
						setTimeout(function() {
							d(false);
							J.cookie.setCookie("shd", R, 365);
							M();
							O();
							w.removeClass("hidepop").setStyle({
								opacity: "0.99999"
							});
							w.get().style["-webkit-transition"] = "opacity 2s ease-in-out";
							popState = false;
							J.logger.trackEvent({
								site: "anjuke-npv",
								page: "share_touch",
								customparam: '{"stp":"show"}'
							});
							window.scrollTo(0, 0);
							J.g("foot_share_fix").setStyle({
								display: "block"
							});
						},
						3000);
					}
				}
			}
		};
	}
	J.mix(f, {
		lazyLoad: E,
		refreshImage: t,
		setCookie: q,
		track: r,
		trackEvent: j,
		toTop: G,
		exposure: new C(),
		autocomplate: y,
		filterPopBox: i,
		slidePopBox: v,
		bindMobile: e,
		getMapShow: z,
		createDropDownMenu: m,
		parseQueryString: x,
		sharePop: a
	});
	k.T = f;
})(J.W);