import React from 'react';
import { View, Text ,ImageBackground,StatusBar} from 'react-native';
import { NavigationActions, StackActions } from 'react-navigation';

class SplashScreen extends React.Component {
  performTimeConsumingTask = async() => {
    return new Promise((resolve) =>
      setTimeout(
        () => { resolve('result') },
        700
      )
    )
  }

  async componentDidMount() {
    // Preload data from an external API
    // Preload data using AsyncStorage
    const data = await this.performTimeConsumingTask();

    if (data !== null) {
   //  this.props.navigation.navigate('Roleselection');

     this.props.navigation.reset([NavigationActions.navigate({ routeName: 'Roleselection' })], 0);
    }
  }

  render() {
    return (
      <View style={styles.viewStyles}>
        {/* <Text style={styles.textStyles}>
          Blitz Reading
        </Text> */}
            {/* <StatusBar barStyle='dark-content' backgroundColor='transparent' ></StatusBar> */}
       <ImageBackground style={{    position: 'absolute',
    margin: 0,
    //  width: Sizes.screen.width,
    height: '100%',
    width: '100%'}} source={require('./image/Splash.jpg')}></ImageBackground>
      </View>
    );
  }
}

const styles = {
  viewStyles: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    //backgroundColor: 'orange'
  },
  textStyles: {
    color: 'white',
    fontSize: 40,
    fontWeight: 'bold'
  }
}

export default SplashScreen;