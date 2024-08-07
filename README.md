[![](https://poggit.pmmp.io/shield.dl/EmptyChunkRemover)](https://poggit.pmmp.io/p/EmptyChunkRemover) [![](https://poggit.pmmp.io/shield.dl.total/EmptyChunkRemover)](https://poggit.pmmp.io/p/EmptyChunkRemover)

## About

This Pocketmine-MP plugin removes all the empty chunks from your world, reducing world size and read/writes to disc. Along with sometimes reducing world load cpu usage on ported maps. 

## When to use?
- Using in-memory world provider 
- Having large maps of skyblock, bedwars, etc gamemodes (void worlds)
- After importing/converting java or pc worlds to bedrock
(mc bedrock and java by default saves empty chunks, increasing world size whereas pmmp does not)
- Storing worlds on a remote server

## Performance

- Took 24.6 secs to convert a world having a total of 10152 chunks out of which 9872 chunks were empty (Remaning chunks: 280)
- World size reduced from 1.62MB to 265KB
#
- Took 1.8 secs to convert a world having total 1057 chunks out of which 0 were empty
#
- Took 15.7mins to convert a world having total 395478 chunks out of which 377748 chunks were empty (Remaning chunks: 17730)
- World size reduced from 96.8MB to 19MB
#
- Took 7.47mins to convert a world having total 191794 chunks out of which 191336 chunks were empty (Remaning chunks: 458)
- World size reduced from 40.5MB to 888KB
#
Above tests were performed on github codespaces (basic)

Please note this is a cpu intensive process

## How to use?
- Just download the ``.phar`` file.
- Upload it to your ``plugins`` folder.
- Upload your worlds to the ``worlds`` folder
- Edit config.yml to your needs
- Restart the server.
- Wait for worlds to be converted (might take more time depending on world size)
- Check ``plugin_data/EmptyChunkRemover`` for your converted worlds

## Config

```yaml
# Configuration Version
config-version: "1.0"

# World names that need to be converted / fixed.
worlds:
- world1
- world2
```

## Todo
- [] Add threading / async to allow background operations
- [] Make it developer friendly
- [] Add estimated time
- [] Add conversion completion %

## Additional Notes

If you encounter any bugs or glitches, please create an issue [here](https://github.com/HGRgamer/EmptyChunkRemover/issues/new).

Any suggestions you may have to improve EmptyChunkRemover are welcome. Feel free to create an issue [here](https://github.com/HGRgamer/EmptyChunkRemover/issues/new).

If you want to contribute to this project, create a pull request [here](https://github.com/HGRgamer/EmptyChunkRemover/pulls).

## Credits
- Logo by [ishmanallenlitchmore](https://www.deviantart.com/ishmanallenlitchmore/gallery) <img src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/c5bcf0c7-4a91-4195-8c76-36e4c285de29/d6buu1p-fa3c9939-ef89-42d0-a6ee-236c5401fa94.png/v1/fill/w_789,h_1013/chunk_of_minecraft_by_ishmanallenlitchmore_d6buu1p-pre.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7ImhlaWdodCI6Ijw9NDE1NiIsInBhdGgiOiJcL2ZcL2M1YmNmMGM3LTRhOTEtNDE5NS04Yzc2LTM2ZTRjMjg1ZGUyOVwvZDZidXUxcC1mYTNjOTkzOS1lZjg5LTQyZDAtYTZlZS0yMzZjNTQwMWZhOTQucG5nIiwid2lkdGgiOiI8PTMyNDAifV1dLCJhdWQiOlsidXJuOnNlcnZpY2U6aW1hZ2Uub3BlcmF0aW9ucyJdfQ.8KFf_nRXwMshTSWXJoVCQ3_5049rnaGtgjLLb6PlRzQ" width="20" height="22">
- Config system by the great [imLuckii](https://github.com/imLuckii)
- Plugin by master HGRgamer