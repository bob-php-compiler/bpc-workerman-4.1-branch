libworkerman_u-4.4a.so:
	./bpc-prepare.sh
	$(MAKE) -C ./Workerman libworkerman

libworkerman:
	bpc -v \
	    -c ../workerman-bpc.conf \
	    -l workerman \
	    --input-file src.list

install-libworkerman:
	cd Workerman && bpc -l workerman --install
